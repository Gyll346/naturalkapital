/**
 * Circular Gallery for YNKI Mitra & Pendukung Kami
 * Faithful Vanilla JS implementation of CircularGallery from React Bits (OGL)
 * Reference: code_Circular Gallery.md
 */
(function () {
  'use strict';

  function initCircularGallery() {
    var container = document.getElementById('ynki-circular-gallery-canvas');
    var logosContainer = document.querySelector('.mitra-logos');
    var wrap = document.getElementById('ynki-circular-gallery-wrap');
    if (!container || !logosContainer) return;

    // Collect all partner logos and titles directly from the existing DOM elements
    var logoCards = logosContainer.querySelectorAll('.mitra-logo-card');
    if (!logoCards || logoCards.length === 0) return;

    var items = [];
    logoCards.forEach(function (card) {
      var img = card.querySelector('img');
      var title = card.getAttribute('title') || (img ? img.getAttribute('alt') : '') || '';
      var src = img ? (img.getAttribute('src') || img.currentSrc) : '';
      var href = card.getAttribute('href') || '';
      if (src) {
        items.push({
          image: src,
          domImg: img,
          text: title,
          href: href
        });
      }
    });

    if (items.length === 0) return;

    // Helper: load OGL from CDN if not already loaded
    function loadScript(src, cb, errCb) {
      if (window.ogl) {
        cb();
        return;
      }
      var existing = document.querySelector('script[src*="ogl"]');
      if (existing) {
        if (window.ogl) {
          cb();
        } else {
          existing.addEventListener('load', cb);
        }
        return;
      }
      var s = document.createElement('script');
      s.src = src;
      s.async = true;
      s.onload = cb;
      s.onerror = errCb;
      document.head.appendChild(s);
    }

    loadScript(
      'https://unpkg.com/ogl@0.0.103/dist/ogl.umd.js',
      function () {
        if (!window.ogl) return;
        setupGallery(window.ogl, container, wrap, logosContainer, items);
      },
      function () {
        console.warn('OGL failed to load; using static partner logos.');
        if (wrap) wrap.style.display = 'none';
        logosContainer.style.display = 'flex';
      }
    );
  }

  function setupGallery(ogl, container, wrap, logosContainer, items) {
    var Camera = ogl.Camera;
    var Mesh = ogl.Mesh;
    var Plane = ogl.Plane;
    var Program = ogl.Program;
    var Renderer = ogl.Renderer;
    var Texture = ogl.Texture;
    var Transform = ogl.Transform;

    function debounce(func, wait) {
      var timeout;
      return function () {
        var context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function () {
          func.apply(context, args);
        }, wait);
      };
    }

    function lerp(p1, p2, t) {
      return p1 + (p2 - p1) * t;
    }

    function getFontSize(font) {
      var match = font.match(/(\d+)px/);
      return match ? parseInt(match[1], 10) : 24;
    }

    function createTextTexture(gl, text, font, color) {
      font = font || 'bold 24px "Plus Jakarta Sans", "Montserrat", sans-serif';
      color = color || '#0e241b';
      var canvas = document.createElement('canvas');
      var ctx = canvas.getContext('2d');
      ctx.font = font;
      var metrics = ctx.measureText(text);
      var textWidth = Math.ceil(metrics.width);
      var textHeight = Math.ceil(getFontSize(font) * 1.3);
      canvas.width = textWidth + 30;
      canvas.height = textHeight + 20;
      ctx.font = font;
      ctx.fillStyle = color;
      ctx.textBaseline = 'middle';
      ctx.textAlign = 'center';
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.fillText(text, canvas.width / 2, canvas.height / 2);
      var texture = new Texture(gl, { generateMipmaps: false });
      texture.image = canvas;
      return { texture: texture, width: canvas.width, height: canvas.height };
    }

    // Title label below each card
    function Title(options) {
      this.gl = options.gl;
      this.plane = options.plane;
      this.text = options.text;
      this.textColor = options.textColor || '#0e241b';
      this.font = options.font || 'bold 24px "Plus Jakarta Sans", "Montserrat", sans-serif';
      this.createMesh();
    }

    Title.prototype.createMesh = function () {
      var res = createTextTexture(this.gl, this.text, this.font, this.textColor);
      var geometry = new Plane(this.gl);
      var program = new Program(this.gl, {
        vertex: `
          attribute vec3 position;
          attribute vec2 uv;
          uniform mat4 modelViewMatrix;
          uniform mat4 projectionMatrix;
          varying vec2 vUv;
          void main() {
            vUv = uv;
            gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
          }
        `,
        fragment: `
          precision highp float;
          uniform sampler2D tMap;
          varying vec2 vUv;
          void main() {
            vec4 color = texture2D(tMap, vUv);
            if (color.a < 0.1) discard;
            gl_FragColor = color;
          }
        `,
        uniforms: { tMap: { value: res.texture } },
        transparent: true
      });
      this.mesh = new Mesh(this.gl, { geometry: geometry, program: program });
      var aspect = res.width / res.height;
      var textHeight = this.plane.scale.y * 0.14;
      var textWidth = textHeight * aspect;
      this.mesh.scale.set(textWidth, textHeight, 1);
      this.mesh.position.y = -this.plane.scale.y * 0.5 - textHeight * 0.55 - 0.1;
      this.mesh.setParent(this.plane);
    };

    // Prepare card texture with white background and centered partner logo
    function prepareCardTexture(gl, src, domImg, onReady) {
      var cardW = 400;
      var cardH = 240;

      // Solid white placeholder so it's never black while loading
      var placeholder = document.createElement('canvas');
      placeholder.width = cardW;
      placeholder.height = cardH;
      var pCtx = placeholder.getContext('2d');
      pCtx.fillStyle = '#ffffff';
      pCtx.fillRect(0, 0, cardW, cardH);

      var texture = new Texture(gl, {
        image: placeholder,
        generateMipmaps: true
      });

      function drawToCanvas(imgElem) {
        var canvas = document.createElement('canvas');
        canvas.width = cardW;
        canvas.height = cardH;
        var ctx = canvas.getContext('2d');

        // Crisp white card
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, cardW, cardH);

        // Subtle clean border
        ctx.strokeStyle = '#d7e8de';
        ctx.lineWidth = 4;
        ctx.strokeRect(2, 2, cardW - 4, cardH - 4);

        // Center partner logo
        var padX = 38;
        var padY = 28;
        var maxW = cardW - padX * 2;
        var maxH = cardH - padY * 2;
        var nw = imgElem.naturalWidth || imgElem.width || maxW;
        var nh = imgElem.naturalHeight || imgElem.height || maxH;
        var scale = Math.min(maxW / nw, maxH / nh, 1);
        var dw = nw * scale;
        var dh = nh * scale;
        var dx = (cardW - dw) / 2;
        var dy = (cardH - dh) / 2;
        ctx.drawImage(imgElem, dx, dy, dw, dh);

        texture.image = canvas;
        texture.needsUpdate = true;
        if (onReady) onReady(cardW, cardH);
      }

      // If already loaded in DOM, render immediately
      if (domImg && domImg.complete && domImg.naturalWidth > 0) {
        drawToCanvas(domImg);
      } else {
        var loader = new Image();
        loader.onload = function () {
          drawToCanvas(loader);
        };
        loader.onerror = function () {
          // If secondary load fails, try using domImg once loaded
          if (domImg) {
            domImg.addEventListener('load', function () {
              drawToCanvas(domImg);
            });
          }
        };
        loader.src = src;
      }

      return texture;
    }

    // Media card item
    function Media(opts) {
      this.extra = 0;
      this.geometry = opts.geometry;
      this.gl = opts.gl;
      this.image = opts.image;
      this.domImg = opts.domImg;
      this.index = opts.index;
      this.length = opts.length;
      this.renderer = opts.renderer;
      this.scene = opts.scene;
      this.screen = opts.screen;
      this.text = opts.text;
      this.href = opts.href;
      this.viewport = opts.viewport;
      this.bend = opts.bend;
      this.textColor = opts.textColor;
      this.borderRadius = opts.borderRadius || 0.05;
      this.font = opts.font;

      this.createShader();
      this.createMesh();
      this.createTitle();
      this.onResize();
    }

    Media.prototype.createShader = function () {
      var self = this;
      var texture = prepareCardTexture(this.gl, this.image, this.domImg, function (w, h) {
        if (self.program && self.program.uniforms.uImageSizes) {
          self.program.uniforms.uImageSizes.value = [w, h];
        }
      });

      this.program = new Program(this.gl, {
        depthTest: false,
        depthWrite: false,
        vertex: `
          precision highp float;
          attribute vec3 position;
          attribute vec2 uv;
          uniform mat4 modelViewMatrix;
          uniform mat4 projectionMatrix;
          uniform float uTime;
          uniform float uSpeed;
          varying vec2 vUv;
          void main() {
            vUv = uv;
            vec3 p = position;
            p.z = (sin(p.x * 4.0 + uTime) * 1.5 + cos(p.y * 2.0 + uTime) * 1.5) * (0.06 + uSpeed * 0.35);
            gl_Position = projectionMatrix * modelViewMatrix * vec4(p, 1.0);
          }
        `,
        fragment: `
          precision highp float;
          uniform vec2 uImageSizes;
          uniform vec2 uPlaneSizes;
          uniform sampler2D tMap;
          uniform float uBorderRadius;
          varying vec2 vUv;

          float roundedBoxSDF(vec2 p, vec2 b, float r) {
            vec2 d = abs(p) - b;
            return length(max(d, vec2(0.0))) + min(max(d.x, d.y), 0.0) - r;
          }

          void main() {
            vec2 ratio = vec2(
              min((uPlaneSizes.x / uPlaneSizes.y) / (uImageSizes.x / uImageSizes.y), 1.0),
              min((uPlaneSizes.y / uPlaneSizes.x) / (uImageSizes.y / uImageSizes.x), 1.0)
            );
            vec2 uv = vec2(
              vUv.x * ratio.x + (1.0 - ratio.x) * 0.5,
              vUv.y * ratio.y + (1.0 - ratio.y) * 0.5
            );
            vec4 color = texture2D(tMap, uv);
            float d = roundedBoxSDF(vUv - 0.5, vec2(0.5 - uBorderRadius), uBorderRadius);
            float edgeSmooth = 0.002;
            float alpha = 1.0 - smoothstep(-edgeSmooth, edgeSmooth, d);
            gl_FragColor = vec4(color.rgb, color.a * alpha);
          }
        `,
        uniforms: {
          tMap: { value: texture },
          uPlaneSizes: { value: [0, 0] },
          uImageSizes: { value: [400, 240] },
          uSpeed: { value: 0 },
          uTime: { value: 100 * Math.random() },
          uBorderRadius: { value: this.borderRadius }
        },
        transparent: true
      });
    };

    Media.prototype.createMesh = function () {
      this.plane = new Mesh(this.gl, {
        geometry: this.geometry,
        program: this.program
      });
      this.plane.setParent(this.scene);
    };

    Media.prototype.createTitle = function () {
      this.title = new Title({
        gl: this.gl,
        plane: this.plane,
        renderer: this.renderer,
        text: this.text,
        textColor: this.textColor,
        font: this.font
      });
    };

    Media.prototype.update = function (scroll, direction) {
      this.plane.position.x = this.x - scroll.current - this.extra;

      var x = this.plane.position.x;
      var H = this.viewport.width / 2;

      if (this.bend === 0) {
        this.plane.position.y = 0;
        this.plane.rotation.z = 0;
      } else {
        var B_abs = Math.abs(this.bend);
        var R = (H * H + B_abs * B_abs) / (2 * B_abs);
        var effectiveX = Math.min(Math.abs(x), H);
        var arc = R - Math.sqrt(R * R - effectiveX * effectiveX);
        if (this.bend > 0) {
          this.plane.position.y = -arc;
          this.plane.rotation.z = -Math.sign(x) * Math.asin(effectiveX / R);
        } else {
          this.plane.position.y = arc;
          this.plane.rotation.z = Math.sign(x) * Math.asin(effectiveX / R);
        }
      }

      this.speed = scroll.current - scroll.last;
      this.program.uniforms.uTime.value += 0.04;
      this.program.uniforms.uSpeed.value = this.speed;

      var planeOffset = this.plane.scale.x / 2;
      var viewportOffset = this.viewport.width / 2;
      this.isBefore = this.plane.position.x + planeOffset < -viewportOffset;
      this.isAfter = this.plane.position.x - planeOffset > viewportOffset;

      if (direction === 'right' && this.isBefore) {
        this.extra -= this.widthTotal;
        this.isBefore = this.isAfter = false;
      }
      if (direction === 'left' && this.isAfter) {
        this.extra += this.widthTotal;
        this.isBefore = this.isAfter = false;
      }
    };

    Media.prototype.onResize = function (opts) {
      opts = opts || {};
      if (opts.screen) this.screen = opts.screen;
      if (opts.viewport) {
        this.viewport = opts.viewport;
        if (this.plane.program.uniforms.uViewportSizes) {
          this.plane.program.uniforms.uViewportSizes.value = [this.viewport.width, this.viewport.height];
        }
      }

      this.scale = this.screen.height / 1400;
      this.plane.scale.y = (this.viewport.height * (580 * this.scale)) / this.screen.height;
      this.plane.scale.x = (this.viewport.width * (820 * this.scale)) / this.screen.width;
      this.plane.program.uniforms.uPlaneSizes.value = [this.plane.scale.x, this.plane.scale.y];
      this.padding = 1.8;
      this.width = this.plane.scale.x + this.padding;
      this.widthTotal = this.width * this.length;
      this.x = this.width * this.index;
    };

    // Main App
    function App(container, opts) {
      opts = opts || {};
      this.container = container;
      this.scrollSpeed = opts.scrollSpeed || 2;
      this.scroll = { ease: opts.scrollEase || 0.02, current: 0, target: 0, last: 0 };
      this.onCheckDebounce = debounce(this.onCheck.bind(this), 250);
      this.bend = typeof opts.bend !== 'undefined' ? opts.bend : 3;
      this.textColor = opts.textColor || '#0e241b';
      this.borderRadius = typeof opts.borderRadius !== 'undefined' ? opts.borderRadius : 0.05;
      this.font = opts.font || 'bold 24px "Plus Jakarta Sans", "Montserrat", sans-serif';

      this.createRenderer();
      this.createCamera();
      this.createScene();
      this.onResize();
      this.createGeometry();
      this.createMedias(opts.items || []);
      this.addEventListeners();
      this.update();
    }

    App.prototype.createRenderer = function () {
      this.renderer = new Renderer({
        alpha: true,
        antialias: true,
        dpr: Math.min(window.devicePixelRatio || 1, 2)
      });
      this.gl = this.renderer.gl;
      this.gl.clearColor(0, 0, 0, 0);
      this.container.innerHTML = '';
      this.container.appendChild(this.gl.canvas);
    };

    App.prototype.createCamera = function () {
      this.camera = new Camera(this.gl);
      this.camera.fov = 45;
      this.camera.position.z = 20;
    };

    App.prototype.createScene = function () {
      this.scene = new Transform();
    };

    App.prototype.createGeometry = function () {
      this.planeGeometry = new Plane(this.gl, {
        heightSegments: 50,
        widthSegments: 100
      });
    };

    App.prototype.createMedias = function (items) {
      // Repeat list for seamless infinite circular looping
      this.mediasImages = items.concat(items);
      var self = this;
      this.medias = this.mediasImages.map(function (data, index) {
        return new Media({
          geometry: self.planeGeometry,
          gl: self.gl,
          image: data.image,
          domImg: data.domImg,
          index: index,
          length: self.mediasImages.length,
          renderer: self.renderer,
          scene: self.scene,
          screen: self.screen,
          text: data.text,
          href: data.href,
          viewport: self.viewport,
          bend: self.bend,
          textColor: self.textColor,
          borderRadius: self.borderRadius,
          font: self.font
        });
      });
    };

    App.prototype.onTouchDown = function (e) {
      this.isDown = true;
      this.dragDistance = 0;
      this.scroll.position = this.scroll.current;
      this.start = e.touches ? e.touches[0].clientX : e.clientX;
      this.startY = e.touches ? e.touches[0].clientY : e.clientY;
    };

    App.prototype.onTouchMove = function (e) {
      if (!this.isDown) return;
      var x = e.touches ? e.touches[0].clientX : e.clientX;
      var y = e.touches ? e.touches[0].clientY : e.clientY;
      var distance = (this.start - x) * (this.scrollSpeed * 0.025);
      this.dragDistance += Math.abs(x - this.start) + Math.abs(y - this.startY);
      this.scroll.target = this.scroll.position + distance;
    };

    App.prototype.onTouchUp = function (e) {
      // Click detection: if user just tapped/clicked without drag, navigate to partner website
      if (this.isDown && this.dragDistance < 10 && e) {
        var clientX = e.clientX || (e.changedTouches ? e.changedTouches[0].clientX : null);
        if (clientX !== null && this.medias) {
          var rect = this.container.getBoundingClientRect();
          var normX = ((clientX - rect.left) / rect.width) * 2 - 1;
          var clickWorldX = (normX * this.viewport.width) / 2;
          var closest = null;
          var minD = Infinity;
          this.medias.forEach(function (m) {
            var d = Math.abs(m.plane.position.x - clickWorldX);
            if (d < minD) {
              minD = d;
              closest = m;
            }
          });
          if (closest && minD < closest.plane.scale.x * 0.6 && closest.href && closest.href !== '#') {
            window.open(closest.href, '_blank', 'noopener,noreferrer');
          }
        }
      }

      this.isDown = false;
      this.onCheck();
    };

    App.prototype.onWheel = function (e) {
      var delta = e.deltaY || e.wheelDelta || e.detail;
      this.scroll.target += (delta > 0 ? this.scrollSpeed : -this.scrollSpeed) * 0.2;
      this.onCheckDebounce();
    };

    App.prototype.onKeyDown = function (e) {
      switch (e.key) {
        case 'ArrowRight':
          e.preventDefault();
          this.scroll.target += this.scrollSpeed * 5;
          this.onCheckDebounce();
          break;
        case 'ArrowLeft':
          e.preventDefault();
          this.scroll.target -= this.scrollSpeed * 5;
          this.onCheckDebounce();
          break;
        case 'Home':
          e.preventDefault();
          this.scroll.target = 0;
          this.onCheckDebounce();
          break;
        default:
          break;
      }
    };

    App.prototype.onCheck = function () {
      if (!this.medias || !this.medias[0]) return;
      var width = this.medias[0].width;
      var itemIndex = Math.round(Math.abs(this.scroll.target) / width);
      var item = width * itemIndex;
      this.scroll.target = this.scroll.target < 0 ? -item : item;
    };

    App.prototype.onResize = function () {
      var w = this.container.clientWidth;
      var h = this.container.clientHeight;
      if (!w || !h) {
        w = this.container.parentElement ? this.container.parentElement.clientWidth : window.innerWidth;
        h = 560;
      }
      this.screen = { width: w, height: h };
      this.renderer.setSize(this.screen.width, this.screen.height);
      this.camera.perspective({
        aspect: this.screen.width / this.screen.height
      });
      var fov = (this.camera.fov * Math.PI) / 180;
      var height = 2 * Math.tan(fov / 2) * this.camera.position.z;
      var width = height * this.camera.aspect;
      this.viewport = { width: width, height: height };
      if (this.medias) {
        var self = this;
        this.medias.forEach(function (media) {
          media.onResize({ screen: self.screen, viewport: self.viewport });
        });
      }
    };

    App.prototype.update = function () {
      // Gentle continuous ambient drift forward when not dragging
      if (!this.isDown) {
        this.scroll.target += 0.015;
      }

      this.scroll.current = lerp(this.scroll.current, this.scroll.target, this.scroll.ease);
      var direction = this.scroll.current > this.scroll.last ? 'right' : 'left';
      if (this.medias) {
        var self = this;
        this.medias.forEach(function (media) {
          media.update(self.scroll, direction);
        });
      }
      this.renderer.render({ scene: this.scene, camera: this.camera });
      this.scroll.last = this.scroll.current;
      this.raf = window.requestAnimationFrame(this.update.bind(this));
    };

    App.prototype.addEventListeners = function () {
      this.boundOnResize = this.onResize.bind(this);
      this.boundOnWheel = this.onWheel.bind(this);
      this.boundOnTouchDown = this.onTouchDown.bind(this);
      this.boundOnTouchMove = this.onTouchMove.bind(this);
      this.boundOnTouchUp = this.onTouchUp.bind(this);
      this.boundOnKeyDown = this.onKeyDown.bind(this);

      window.addEventListener('resize', this.boundOnResize);
      this.container.addEventListener('wheel', this.boundOnWheel, { passive: true });
      this.container.addEventListener('mousedown', this.boundOnTouchDown);
      window.addEventListener('mousemove', this.boundOnTouchMove);
      window.addEventListener('mouseup', this.boundOnTouchUp);

      this.container.addEventListener('touchstart', this.boundOnTouchDown, { passive: true });
      window.addEventListener('touchmove', this.boundOnTouchMove, { passive: true });
      window.addEventListener('touchend', this.boundOnTouchUp);

      this.container.addEventListener('keydown', this.boundOnKeyDown);
    };

    // Reveal container and hide static fallback
    if (wrap) wrap.style.display = 'block';
    logosContainer.style.display = 'none';

    // Instantiate with exact parameters from code_Circular Gallery.md
    var app = new App(container, {
      items: items,
      bend: 3,
      textColor: '#0e241b',
      borderRadius: 0.05,
      scrollSpeed: 2,
      scrollEase: 0.02,
      font: 'bold 24px "Plus Jakarta Sans", "Montserrat", sans-serif'
    });

    // Run immediate onResize once container is laid out
    setTimeout(function () {
      app.onResize();
    }, 50);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCircularGallery);
  } else {
    initCircularGallery();
  }
})();
