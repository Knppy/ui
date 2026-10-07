const inputOtp = (value = '', maxLength = 6) => ({
    value: String(value ?? '').slice(0, maxLength),
    maxLength: Number(maxLength),
    focused: false,
    selection: 0,
    get activeIndex() {
        return Math.min(this.selection, this.maxLength - 1);
    },
    character(index) {
        return this.value[index] ?? '';
    },
    isActive(index) {
        return this.focused && this.activeIndex === index;
    },
    hasFakeCaret(index) {
        return this.isActive(index) && !this.character(index);
    },
    update(event) {
        this.value = event.currentTarget.value.slice(0, this.maxLength);
        this.selection = event.currentTarget.selectionStart ?? this.value.length;

        if (this.value.length === this.maxLength) {
            this.$dispatch('complete', this.value);
        }
    },
    updateSelection(event) {
        this.selection = event.currentTarget.selectionStart ?? this.value.length;
    },
    focusAt(index) {
        const position = Math.min(index, this.value.length);

        this.$refs.input.focus();
        this.$refs.input.setSelectionRange(position, position);
        this.selection = position;
    },
});

export default function (Alpine) {
    Alpine.data('uiInputOtp', inputOtp);
}
