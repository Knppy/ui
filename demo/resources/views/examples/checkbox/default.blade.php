<x-ui.field-group class="max-w-sm">
    <x-ui.field orientation="horizontal">
        <x-ui.checkbox id="terms-checkbox" name="terms-checkbox" />
        <x-ui.label for="terms-checkbox">Accept terms and conditions</x-ui.label>
    </x-ui.field>
    <x-ui.field orientation="horizontal">
        <x-ui.checkbox id="terms-checkbox-2" name="terms-checkbox-2" defaultChecked />
        <x-ui.field-content>
            <x-ui.field-label for="terms-checkbox-2"> Accept terms and conditions </x-ui.field-label>
            <x-ui.field-description> By clicking this checkbox, you agree to the terms. </x-ui.field-description>
        </x-ui.field-content>
    </x-ui.field>
    <x-ui.field orientation="horizontal" data-disabled>
        <x-ui.checkbox id="toggle-checkbox" name="toggle-checkbox" disabled />
        <x-ui.field-label for="toggle-checkbox">Enable notifications</x-ui.field-label>
    </x-ui.field>
    <x-ui.field-label>
        <x-ui.field orientation="horizontal">
            <x-ui.checkbox id="toggle-checkbox-2" name="toggle-checkbox-2" />
            <x-ui.field-content>
                <x-ui.field-title>Enable notifications</x-ui.field-title>
                <x-ui.field-description> You can enable or disable notifications at any time. </x-ui.field-description>
            </x-ui.field-content>
        </x-ui.field>
    </x-ui.field-label>
</x-ui.field-group>
