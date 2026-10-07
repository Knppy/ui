const toggle = (pressed = false) => ({
    pressed,
    toggle() {
        this.pressed = !this.pressed;
    },
});

export default function (Alpine) {
    Alpine.data('uiToggle', toggle);
}
