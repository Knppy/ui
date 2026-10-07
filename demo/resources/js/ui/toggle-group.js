const toggleGroup = (value = null, type = 'single') => ({
    value: type === 'multiple' ? (Array.isArray(value) ? value : []) : value,
    type,
    isPressed(item) {
        return this.type === 'multiple' ? this.value.includes(item) : this.value === item;
    },
    toggle(item) {
        if (this.type === 'multiple') {
            this.value = this.isPressed(item)
                ? this.value.filter((value) => value !== item)
                : [...this.value, item];
            return;
        }

        this.value = this.value === item ? null : item;
    },
});

export default function (Alpine) {
    Alpine.data('uiToggleGroup', toggleGroup);
}
