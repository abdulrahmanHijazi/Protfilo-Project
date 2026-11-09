<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\LanguageItem;
use App\Models\Project;
use App\Models\SceneModel;
use App\Models\Service;
use App\Models\Skill;
use App\Settings\SiteSettings;
use App\Support\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    private function locale(): string
    {
        $locale = app()->getLocale();

        return in_array($locale, ['en', 'ar'], true) ? $locale : 'en';
    }

    private function dir(): string
    {
        return $this->locale() === 'ar' ? 'rtl' : 'ltr';
    }

    private function presentSettings(SiteSettings $settings): array
    {
        $data = $settings->toArray();

        $personalImage = $data['personal_image'] ?? null;
        $logo = $data['logo'] ?? null;

        $data['avatar_url'] = $personalImage ? Storage::disk('public')->url($personalImage) : null;
        $data['profile_image'] = $data['avatar_url'];
        $data['logo_url'] = $logo ? Storage::disk('public')->url($logo) : null;

        if ($settings->scene_follow_theme) {
            $theme = Theme::colors($settings);
            $data['particles_color'] = $theme['theme_accent_light'];
            $data['scene_bloom_color'] = $theme['theme_accent'];
        }

        // Socials grouped for frontend convenience (frontend also reads flat keys).
        $data['socials'] = [
            'github' => $data['github'] ?? null,
            'linkedin' => $data['linkedin'] ?? null,
            'twitter' => $data['x_twitter'] ?? null,
            'facebook' => $data['facebook'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'gitlab' => $data['gitlab'] ?? null,
        ];

        return $data;
    }

    private function presentProject(Project $project): array
    {
        $data = $project->toArray();

        $cover = $project->getFirstMediaUrl('cover');
        $gallery = $project->getMedia('gallery')->map(fn ($m) => $m->getUrl())->values()->all();

        $data['cover'] = $cover ?: null;
        $data['image'] = $cover ?: null;
        $data['gallery'] = $gallery;
        // Aliases expected by the Vue frontend (DB column is `techs`).
        $data['technologies'] = $project->techs ?? [];
        $data['live_url'] = $project->project_url;
        $data['demo_url'] = $project->project_url;
        $data['repo_url'] = $project->github_url;

        return $data;
    }

    /** @param iterable<Project> $projects */
    private function presentProjects(iterable $projects): array
    {
        return collect($projects)->map(fn (Project $p) => $this->presentProject($p))->all();
    }

    /** Active 3D models grouped by placement; empty when 3D is disabled in settings. */
    private function scenes(SiteSettings $settings): array
    {
        if (! $settings->enable_3d) {
            return [];
        }

        return SceneModel::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->get()
            ->groupBy('placement')
            ->map(fn ($models) => $models->map(fn (SceneModel $m) => $m->toScene())->values())
            ->all();
    }

    public function home(SiteSettings $settings): Response
    {
        return Inertia::render('Home', [
            'scenes' => $this->scenes($settings),
            'experiences' => Experience::orderBy('sort')->get(),
            'locale' => $this->locale(),
            'dir' => $this->dir(),
            'settings' => $this->presentSettings($settings),
            'projects' => $this->presentProjects(Project::orderBy('sort')->get()),
            'services' => Service::orderBy('sort')->where('is_active', true)->get(),
            'skills' => Skill::orderBy('sort')->get(),
            'languages' => LanguageItem::orderBy('sort')->get(),
            'certificates' => Certificate::orderBy('sort')->get(),
            'education' => Education::orderBy('sort')->get(),
            'educations' => Education::orderBy('sort')->get(),
        ]);
    }

    public function about(SiteSettings $settings): Response
    {
        return Inertia::render('About', [
            'scenes' => $this->scenes($settings),
            'experiences' => Experience::orderBy('sort')->get(),
            'locale' => $this->locale(),
            'dir' => $this->dir(),
            'settings' => $this->presentSettings($settings),
            'skills' => Skill::orderBy('sort')->get(),
            'languages' => LanguageItem::orderBy('sort')->get(),
            'education' => Education::orderBy('sort')->get(),
            'educations' => Education::orderBy('sort')->get(),
            'certificates' => Certificate::orderBy('sort')->get(),
        ]);
    }

    public function projects(SiteSettings $settings): Response
    {
        return Inertia::render('Projects/Index', [
            'scenes' => $this->scenes($settings),
            'locale' => $this->locale(),
            'dir' => $this->dir(),
            'settings' => $this->presentSettings($settings),
            'projects' => $this->presentProjects(Project::orderBy('sort')->get()),
        ]);
    }

    public function show(SiteSettings $settings, Project $project): Response
    {
        return Inertia::render('Projects/Show', [
            'locale' => $this->locale(),
            'dir' => $this->dir(),
            'settings' => $this->presentSettings($settings),
            'project' => $this->presentProject($project),
        ]);
    }

    public function contact(SiteSettings $settings): Response
    {
        return Inertia::render('Contact', [
            'scenes' => $this->scenes($settings),
            'locale' => $this->locale(),
            'dir' => $this->dir(),
            'settings' => $this->presentSettings($settings),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Message sent successfully.');
    }
}
