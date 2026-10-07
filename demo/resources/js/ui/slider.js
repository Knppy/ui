const slider = (value = 0, min = 0, max = 100, step = 1) => ({
    value: Number(value),
    min: Number(min),
    max: Number(max),
    step: Number(step),
    get percentage() {
        const range = this.max - this.min;

        return range > 0 ? Math.min(100, Math.max(0, ((this.value - this.min) / range) * 100)) : 0;
    },
});

export default function (Alpine) {
    Alpine.data('uiSlider', slider);
}
