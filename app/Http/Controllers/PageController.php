<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

use function abort_if;

class PageController
{
    public function landing(): Response
    {
        $services = Service::query()
            ->where('status', true)
            ->orderBy('sequence')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        $testimonials = Testimonial::query()
            ->where('status', true)
            ->orderBy('sequence')
            ->orderByDesc('created_at')
            ->get();

        $news = News::query()
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Landing', [
            'services' => $services,
            'testimonials' => $testimonials,
            'news' => $news,
        ]);
    }

    public function services(): Response
    {
        $services = Service::query()
            ->where('status', true)
            ->orderBy('sequence')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Service', [
            'services' => $services,
        ]);
    }

    public function serviceDetail(string $slug): Response
    {
        $service = Service::where('slug', $slug)
            ->with('products')
            ->firstOrFail();

        $otherServices = Service::query()
            ->where('status', true)
            ->where('id', '!=', $service->id)
            ->orderBy('sequence')
            ->orderByDesc('created_at')
            ->take(9)
            ->get();

        return Inertia::render('ServiceDetail', [
            'service' => $service,
            'otherServices' => $otherServices,
        ]);
    }

    public function about(): Response
    {
        $teamMembers = TeamMember::orderBy('sequence')->get();

        return Inertia::render('AboutUs', [
            'teamMembers' => $teamMembers,
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('ContactUs');
    }

    public function career(): Response
    {
        $jobs = $this->jobs();

        return Inertia::render('Career', [
            'jobs' => $jobs,
        ]);
    }

    public function careerDetail(string $jobId): Response
    {
        $jobs = $this->jobs();
        $job = collect($jobs)->firstWhere('id', (int) $jobId);

        abort_if($job === null, 404);

        return Inertia::render('CareerDetail', [
            'job' => $job,
            'jobs' => $jobs,
        ]);
    }

    private function jobs(): array
    {
        $jobs = [
            [
                'id' => 1,
                'title' => 'Senior HR Consultant',
                'category' => 'HR Consulting',
                'summary' => 'Lead retainer clients on complex employee relations cases, coach managers through sensitive changes, and deliver compliance audits. You will map processes, create policies, and prepare training that keeps teams confident during transitions. This role mixes strategic advisory with hands-on delivery, so you will spend time in workshops, producing toolkits, and presenting recommendations to stakeholders across the region.',
                'description' => 'You will partner directly with business owners to deliver pragmatic HR advice and bespoke documentation that keeps them compliant and confident. From disciplinary and grievance investigations to restructuring programmes, you will design people plans that balance empathy and risk. You will also facilitate learning sessions for managers, keep our knowledge base sharp, and ensure our playbooks stay current with the latest employment law changes.',
                'location' => 'London, United Kingdom',
                'type' => 'Full Time',
                'level' => 'Senior',
                'department' => 'Client Advisory',
                'responsibilities' => [
                    'Diagnose client HR risks and design remediation plans with measurable milestones.',
                    'Facilitate workshops and clinics for line managers on people leadership topics.',
                    'Create toolkits, policies, and templates that are practical and on-brand.',
                ],
                'requirements' => [
                    'Proven experience leading complex ER cases end to end.',
                    'Up-to-date knowledge of UK employment law and best practice.',
                    'Strong stakeholder management and presentation skills.',
                ],
            ],
            [
                'id' => 2,
                'title' => 'People Operations Lead',
                'category' => 'People Operations',
                'summary' => 'Own the people ops engine that powers onboarding, payroll inputs, benefits, and HRIS hygiene. You will streamline workflows, remove friction for employees, and ensure data accuracy for reporting. The role needs someone who loves process, automation, and partnering closely with finance to keep everything running smoothly and on time each month.',
                'description' => 'You will design and maintain the operating rhythm for our people team. From pre-boarding through offboarding, you will refine checklists, automate approvals, and keep documentation consistent. You will be the bridge between HR and finance to reconcile payroll changes, and you will monitor service levels so colleagues feel supported at every touchpoint.',
                'location' => 'Hybrid - Manchester',
                'type' => 'Full Time',
                'level' => 'Mid-Senior',
                'department' => 'Operations',
                'responsibilities' => [
                    'Standardise onboarding/offboarding flows and automate reminders.',
                    'Maintain HRIS data quality and drive adoption across the team.',
                    'Coordinate payroll inputs and benefit updates with finance partners.',
                ],
                'requirements' => [
                    'Hands-on HR operations experience in a scaling organisation.',
                    'Fluency with HRIS platforms and process automation tools.',
                    'A continuous improvement mindset with strong documentation skills.',
                ],
            ],
            [
                'id' => 3,
                'title' => 'Talent Acquisition Partner',
                'category' => 'Talent',
                'summary' => 'Shape the candidate experience from first touch to offer acceptance. You will run end-to-end searches, build diverse pipelines, and coach hiring managers on structured interviews. Storytelling is key: you will translate our value proposition into outreach, events, and content that attracts the right people for each role.',
                'description' => 'As a Talent Acquisition Partner you will own searches across consulting, operations, and product. You will design scorecards, run inclusive processes, and keep candidates informed at every stage. Beyond filling roles, you will contribute to employer brand campaigns, source at events, and experiment with new channels to reach niche profiles.',
                'location' => 'Remote, GMT ±2',
                'type' => 'Contract to Permanent',
                'level' => 'Mid',
                'department' => 'Talent',
                'responsibilities' => [
                    'Define role scorecards and interview plans with hiring managers.',
                    'Source proactively through events, referrals, and targeted outreach.',
                    'Run debriefs, manage offers, and ensure an equitable candidate journey.',
                ],
                'requirements' => [
                    'Experience running full-cycle recruitment in professional services.',
                    'Comfort with sourcing tools and employer brand storytelling.',
                    'Ability to manage multiple searches with pace and quality.',
                ],
            ],
        ];

        return $jobs;
    }

    public function news(): Response
    {
        $news = News::query()
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('News', [
            'news' => $news,
        ]);
    }

    public function newsDetail(string $slug): Response
    {
        $news = News::where('slug', $slug)->with('categories')->first();
        $otherNews = News::take(2)->get();

        return Inertia::render('NewsDetail', [
            'news' => $news,
            'otherNews' => $otherNews,
        ]);
    }

    public function companyHandbook(): Response
    {
        return Inertia::render('CompanyHandbook');
    }

    public function products(): Response
    {
        $products = Product::query()
            ->where('status', true)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Products', [
            'products' => $products,
        ]);
    }

    public function productDetail(string $slug): Response
    {
        $product = Product::where('slug', $slug)
            ->with('services')
            ->firstOrFail();

        $products = Product::query()
            ->where('status', true)
            ->where('id', '!=', $product->id)
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return Inertia::render('ProductDetail', [
            'product' => $product,
            'products' => $products,
        ]);
    }

    public function payment(string $slug): Response
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        return Inertia::render('Payment', [
            'product' => $product,
        ]);
    }
}
