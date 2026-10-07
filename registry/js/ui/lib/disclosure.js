export const disclosure = (open = false) => ({
    open,
    close() {
        this.open = false;
    },
    toggle() {
        this.open = !this.open;
    },
});
