<x-ui.card class="w-full max-w-sm">
    <x-ui.card-header>
        <x-ui.card-title>Login to your account</x-ui.card-title>
        <x-ui.card-description> Enter your email below to login to your account </x-ui.card-description>
        <x-ui.card-action>
            <x-ui.button variant="link">Sign Up</x-ui.button>
        </x-ui.card-action>
    </x-ui.card-header>
    <x-ui.card-content>
        <form>
            <div class="flex flex-col gap-6">
                <div class="grid gap-2">
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input id="email" type="email" placeholder="m@example.com" required />
                </div>
                <div class="grid gap-2">
                    <div class="flex items-center">
                        <x-ui.label for="password">Password</x-ui.label>
                        <a href="#" class="ml-auto inline-block text-sm underline-offset-4 hover:underline">
                            Forgot your password?
                        </a>
                    </div>
                    <x-ui.input id="password" type="password" required />
                </div>
            </div>
        </form>
    </x-ui.card-content>
    <x-ui.card-footer class="flex-col gap-2">
        <x-ui.button type="submit" class="w-full"> Login </x-ui.button>
        <x-ui.button variant="outline" class="w-full"> Login with Google </x-ui.button>
    </x-ui.card-footer>
</x-ui.card>
