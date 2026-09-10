<x-ui.tabs value="overview" class="w-[400px]">
    <x-ui.tabs-list>
        <x-ui.tabs-trigger value="overview">Overview</x-ui.tabs-trigger>
        <x-ui.tabs-trigger value="analytics">Analytics</x-ui.tabs-trigger>
        <x-ui.tabs-trigger value="reports">Reports</x-ui.tabs-trigger>
        <x-ui.tabs-trigger value="settings">Settings</x-ui.tabs-trigger>
    </x-ui.tabs-list>
    <x-ui.tabs-content value="overview">
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Overview</x-ui.card-title>
                <x-ui.card-description>
                    View your key metrics and recent project activity. Track progress across all your active projects.
                </x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="text-muted-foreground text-sm">
                You have 12 active projects and 3 pending tasks.
            </x-ui.card-content>
        </x-ui.card>
    </x-ui.tabs-content>
    <x-ui.tabs-content value="analytics">
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Analytics</x-ui.card-title>
                <x-ui.card-description>
                    Track performance and user engagement metrics. Monitor trends and identify growth opportunities.
                </x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="text-muted-foreground text-sm">
                Page views are up 25% compared to last month.
            </x-ui.card-content>
        </x-ui.card>
    </x-ui.tabs-content>
    <x-ui.tabs-content value="reports">
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Reports</x-ui.card-title>
                <x-ui.card-description>
                    Generate and download your detailed reports. Export data in multiple formats for analysis.
                </x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="text-muted-foreground text-sm">
                You have 5 reports ready and available to export.
            </x-ui.card-content>
        </x-ui.card>
    </x-ui.tabs-content>
    <x-ui.tabs-content value="settings">
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Settings</x-ui.card-title>
                <x-ui.card-description>
                    Manage your account preferences and options. Customize your experience to fit your needs.
                </x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="text-muted-foreground text-sm">
                Configure notifications, security, and themes.
            </x-ui.card-content>
        </x-ui.card>
    </x-ui.tabs-content>
</x-ui.tabs>
