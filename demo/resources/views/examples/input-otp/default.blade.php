<x-ui.input-otp name="code" maxlength="6" inputmode="numeric" value="123456">
    <x-ui.input-otp-group>
        @foreach (range(0, 5) as $index)
            <x-ui.input-otp-slot :index="$index" />
        @endforeach
    </x-ui.input-otp-group>
</x-ui.input-otp>
