const calendar = (value = null, mode = 'single', initialMonth = null, showOutsideDays = true, min = null, max = null, startMonth = null, endMonth = null) => ({
    value,
    mode,
    showOutsideDays,
    min,
    max,
    startMonth,
    endMonth,
    month: null,
    focusedDate: null,
    dayFocused: false,
    init() {
        const selected = this.mode === 'range' ? this.value?.from : (Array.isArray(this.value) ? this.value[0] : this.value);
        const date = this.parseDate(initialMonth || selected) ?? new Date();

        this.month = new Date(date.getFullYear(), date.getMonth(), 1);
        this.focusedDate = selected || this.dateKey(date);
    },
    parseDate(value) {
        if (!value) {
            return null;
        }

        const [year, month, day] = String(value).split('-').map(Number);
        const date = new Date(year, month - 1, day || 1);

        return Number.isNaN(date.getTime()) ? null : date;
    },
    dateKey(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    },
    get monthLabel() {
        return this.month?.toLocaleDateString(undefined, { month: 'long', year: 'numeric' }) ?? '';
    },
    get displayMonth() {
        return this.month?.getMonth() ?? 0;
    },
    get displayYear() {
        return this.month?.getFullYear() ?? new Date().getFullYear();
    },
    get monthOptions() {
        return Array.from({ length: 12 }, (_, value) => ({
            value,
            label: new Date(2024, value, 1).toLocaleDateString(undefined, { month: 'short' }),
            disabled: !this.isMonthWithinBounds(new Date(this.displayYear, value, 1)),
        }));
    },
    get yearOptions() {
        const current = new Date().getFullYear();
        const first = this.parseDate(this.startMonth)?.getFullYear() ?? current - 100;
        const last = this.parseDate(this.endMonth)?.getFullYear() ?? current + 100;

        return Array.from({ length: Math.max(0, last - first + 1) }, (_, index) => first + index);
    },
    get weekdays() {
        const sunday = new Date(2024, 0, 7);

        return Array.from({ length: 7 }, (_, index) => {
            const date = new Date(sunday);
            date.setDate(sunday.getDate() + index);

            return {
                short: date.toLocaleDateString(undefined, { weekday: 'short' }),
                long: date.toLocaleDateString(undefined, { weekday: 'long' }),
            };
        });
    },
    get weeks() {
        if (!this.month) {
            return [];
        }

        const first = new Date(this.month.getFullYear(), this.month.getMonth(), 1);
        const start = new Date(first);
        start.setDate(first.getDate() - first.getDay());

        return Array.from({ length: 6 }, (_, week) => Array.from({ length: 7 }, (_, weekday) => {
            const date = new Date(start);
            date.setDate(start.getDate() + week * 7 + weekday);
            const key = this.dateKey(date);

            return {
                date: key,
                label: date.getDate(),
                outside: date.getMonth() !== this.month.getMonth(),
                today: key === this.dateKey(new Date()),
                disabled: (this.min && key < this.min) || (this.max && key > this.max),
            };
        }));
    },
    isSelected(date) {
        if (this.mode === 'multiple') {
            return Array.isArray(this.value) && this.value.includes(date);
        }

        return this.mode === 'single' && this.value === date;
    },
    isRangeStart(date) {
        return this.mode === 'range' && this.value?.from === date;
    },
    isRangeEnd(date) {
        return this.mode === 'range' && this.value?.to === date;
    },
    isRangeMiddle(date) {
        return this.mode === 'range' && this.value?.from && this.value?.to && date > this.value.from && date < this.value.to;
    },
    select(date) {
        if (this.mode === 'multiple') {
            const values = Array.isArray(this.value) ? this.value : [];
            this.value = values.includes(date) ? values.filter((item) => item !== date) : [...values, date];
        } else if (this.mode === 'range') {
            this.value = !this.value?.from || this.value?.to || date < this.value.from
                ? { from: date, to: null }
                : { from: this.value.from, to: date };
        } else {
            this.value = date;
        }

        this.focusedDate = date;
        this.$dispatch('change', this.value);
    },
    focusDay(date) {
        this.focusedDate = date;
        this.dayFocused = true;
    },
    blurDay() {
        this.dayFocused = false;
    },
    isMonthWithinBounds(date) {
        const key = this.dateKey(new Date(date.getFullYear(), date.getMonth(), 1)).slice(0, 7);

        return (!this.startMonth || key >= String(this.startMonth).slice(0, 7)) &&
            (!this.endMonth || key <= String(this.endMonth).slice(0, 7));
    },
    setMonth(month) {
        const next = new Date(this.displayYear, Number(month), 1);

        if (this.isMonthWithinBounds(next)) {
            this.month = next;
            this.focusedDate = this.dateKey(next);
        }
    },
    setYear(year) {
        const next = new Date(Number(year), this.displayMonth, 1);

        if (!this.isMonthWithinBounds(next)) {
            const boundary = Number(year) === this.parseDate(this.startMonth)?.getFullYear() ? this.startMonth : this.endMonth;
            this.month = this.parseDate(boundary);
        } else {
            this.month = next;
        }

        this.focusedDate = this.dateKey(this.month);
    },
    moveMonth(offset) {
        const next = new Date(this.month.getFullYear(), this.month.getMonth() + offset, 1);

        if (!this.isMonthWithinBounds(next)) {
            return;
        }

        this.month = next;
        this.focusedDate = this.dateKey(this.month);
        this.$nextTick(() => this.$root.querySelector(`[data-date="${this.focusedDate}"]`)?.focus());
    },
    focusDate(date) {
        const next = this.parseDate(date);

        this.focusedDate = date;
        if (next.getMonth() !== this.month.getMonth() || next.getFullYear() !== this.month.getFullYear()) {
            this.month = new Date(next.getFullYear(), next.getMonth(), 1);
        }
        this.$nextTick(() => this.$root.querySelector(`[data-date="${date}"]`)?.focus());
    },
    handleDayKeydown(event, date) {
        const offsets = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 };

        if (offsets[event.key] !== undefined) {
            event.preventDefault();
            const next = this.parseDate(date);
            next.setDate(next.getDate() + offsets[event.key]);
            this.focusDate(this.dateKey(next));
        } else if (event.key === 'Home' || event.key === 'End') {
            event.preventDefault();
            const next = this.parseDate(date);
            next.setDate(next.getDate() + (event.key === 'Home' ? -next.getDay() : 6 - next.getDay()));
            this.focusDate(this.dateKey(next));
        } else if (event.key === 'PageUp' || event.key === 'PageDown') {
            event.preventDefault();
            this.moveMonth(event.key === 'PageUp' ? -1 : 1);
        }
    },
});

export default function (Alpine) {
    Alpine.data('uiCalendar', calendar);
}
