require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$members = App\Models\TeamMember::with('category')->get();
echo "Total members in DB: " . $members->count() . "\n";
foreach ($members as $m) {
    echo "- ID: {$m->id} | Name: {$m->full_name} | Role: {$m->position} | Category: " . ($m->category->category_name ?? 'None') . " | Photo: {$m->photo_path}\n";
}
