@props([
    'value' => null,
    'mode' => 'single',
    'month' => null,
    'name' => null,
    'captionLayout' => 'label',
    'showOutsideDays' => true,
    'min' => null,
    'max' => null,
    'startMonth' => null,
    'endMonth' => null,
])

@php
    $initialMonth = $month ?? (is_array($value) ? ($value['from'] ?? $value[0] ?? null) : $value);
@endphp

<div
    x-data="uiCalendar(@js($value), @js($mode), @js($initialMonth), @js($showOutsideDays), @js($min), @js($max), @js($startMonth), @js($endMonth))"
    x-modelable="value"
    data-slot="calendar"
    {{ $attributes->whereStartsWith(['x-model', 'wire:model']) }}
    {{ $attributes->whereDoesntStartWith(['x-model', 'wire:model'])->twMerge(['class' => 'group/calendar bg-background w-fit p-3 [--cell-size:--spacing(8)]']) }}
>
    <div class="relative flex flex-col gap-4">
        @if ($captionLayout === 'label')
            <div
                class="flex h-(--cell-size) items-center justify-center px-(--cell-size) text-sm font-medium"
                aria-live="polite"
                x-text="monthLabel"
            ></div>
        @else
            <div
                class="flex h-(--cell-size) items-center justify-center gap-1.5 px-(--cell-size) text-sm font-medium"
                aria-live="polite"
            >
                @if (in_array($captionLayout, ['dropdown', 'dropdown-months'], true))
                    <label class="focus-within:border-ring focus-within:ring-ring/50 relative rounded-md border border-transparent focus-within:ring-[3px]">
                        <span class="flex h-8 items-center gap-1 rounded-md pr-1 pl-2" aria-hidden="true">
                            <span x-text="monthOptions[displayMonth]?.label"></span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted-foreground size-3.5"><path d="m6 9 6 6 6-6" /></svg>
                        </span>
                        <select
                            aria-label="Choose the month"
                            data-slot="calendar-month-select"
                            x-bind:value="displayMonth"
                            x-on:change="setMonth($event.currentTarget.value)"
                            class="absolute inset-0 cursor-pointer opacity-0"
                        >
                            <template x-for="option in monthOptions" :key="option.value">
                                <option
                                    x-bind:value="option.value"
                                    x-bind:disabled="option.disabled"
                                    x-text="option.label"
                                ></option>
                            </template>
                        </select>
                    </label>
                @else
                    <span x-text="monthOptions[displayMonth]?.label"></span>
                @endif

                @if (in_array($captionLayout, ['dropdown', 'dropdown-years'], true))
                    <label class="focus-within:border-ring focus-within:ring-ring/50 relative rounded-md border border-transparent focus-within:ring-[3px]">
                        <span class="flex h-8 items-center gap-1 rounded-md pr-1 pl-2" aria-hidden="true">
                            <span x-text="displayYear"></span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted-foreground size-3.5"><path d="m6 9 6 6 6-6" /></svg>
                        </span>
                        <select
                            aria-label="Choose the year"
                            data-slot="calendar-year-select"
                            x-bind:value="displayYear"
                            x-on:change="setYear($event.currentTarget.value)"
                            class="absolute inset-0 cursor-pointer opacity-0"
                        >
                            <template x-for="year in yearOptions" :key="year">
                                <option x-bind:value="year" x-text="year"></option>
                            </template>
                        </select>
                    </label>
                @else
                    <span x-text="displayYear"></span>
                @endif
            </div>
        @endif
        <div class="pointer-events-none absolute inset-x-0 top-0 flex items-center justify-between">
            <button
                type="button"
                aria-label="Previous month"
                x-on:click="moveMonth(-1)"
                class="hover:bg-accent hover:text-accent-foreground focus-visible:border-ring focus-visible:ring-ring/50 pointer-events-auto inline-flex size-(--cell-size) items-center justify-center rounded-md outline-none focus-visible:ring-[3px]"
            >
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4"><path d="m15 18-6-6 6-6" /></svg>
            </button>
            <button
                type="button"
                aria-label="Next month"
                x-on:click="moveMonth(1)"
                class="hover:bg-accent hover:text-accent-foreground focus-visible:border-ring focus-visible:ring-ring/50 pointer-events-auto inline-flex size-(--cell-size) items-center justify-center rounded-md outline-none focus-visible:ring-[3px]"
            >
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4"><path d="m9 18 6-6-6-6" /></svg>
            </button>
        </div>
        <table role="grid" class="w-full border-collapse" x-bind:aria-label="monthLabel">
            <thead>
                <tr class="flex">
                    <template x-for="weekday in weekdays" :key="weekday.long">
                        <th
                            scope="col"
                            class="text-muted-foreground flex-1 rounded-md text-[0.8rem] font-normal select-none"
                        >
                            <span x-bind:aria-label="weekday.long" x-text="weekday.short"></span>
                        </th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <template x-for="week in weeks" :key="week[0].date">
                    <tr class="mt-2 flex w-full">
                        <template x-for="day in week" :key="day.date">
                            <td
                                class="group/day relative aspect-square h-full w-full p-0 text-center select-none"
                                x-bind:data-selected="
                                    isSelected(day.date) ||
                                    isRangeStart(day.date) ||
                                    isRangeMiddle(day.date) ||
                                    isRangeEnd(day.date) ||
                                    null
                                "
                                x-bind:data-today="day.today || null"
                                x-bind:data-outside="day.outside || null"
                                x-bind:data-focused="(dayFocused && focusedDate === day.date) || null"
                                x-bind:class="{ invisible: ! showOutsideDays && day.outside }"
                            >
                                <button
                                    type="button"
                                    x-bind:data-date="day.date"
                                    x-bind:data-selected-single="isSelected(day.date) || null"
                                    x-bind:data-range-start="isRangeStart(day.date) || null"
                                    x-bind:data-range-middle="isRangeMiddle(day.date) || null"
                                    x-bind:data-range-end="isRangeEnd(day.date) || null"
                                    x-bind:aria-label="
                                        new Date(`${day.date}T00:00:00`).toLocaleDateString(undefined, {
                                            dateStyle: 'full',
                                        })
                                    "
                                    x-bind:aria-selected="
                                        isSelected(day.date) ||
                                        isRangeStart(day.date) ||
                                        isRangeMiddle(day.date) ||
                                        isRangeEnd(day.date)
                                    "
                                    x-bind:disabled="day.disabled"
                                    x-bind:tabindex="focusedDate === day.date ? 0 : -1"
                                    x-on:focus="focusDay(day.date)"
                                    x-on:blur="blurDay()"
                                    x-on:click="select(day.date)"
                                    x-on:keydown="handleDayKeydown($event, day.date)"
                                    class="hover:bg-accent hover:text-accent-foreground focus-visible:border-ring focus-visible:ring-ring/50 group-data-[focused=true]/day:border-ring group-data-[focused=true]/day:ring-ring/50 group-data-[outside=true]/day:text-muted-foreground group-data-[today=true]/day:bg-accent group-data-[today=true]/day:text-accent-foreground data-[range-end=true]:bg-primary data-[range-end=true]:text-primary-foreground data-[range-middle=true]:bg-accent data-[range-start=true]:bg-primary data-[range-start=true]:text-primary-foreground data-[selected-single=true]:bg-primary data-[selected-single=true]:text-primary-foreground relative z-10 flex aspect-square size-auto w-full min-w-(--cell-size) items-center justify-center rounded-md border border-transparent text-sm leading-none font-normal outline-none group-data-[focused=true]/day:ring-[3px] focus-visible:ring-[3px] disabled:pointer-events-none disabled:opacity-50 data-[range-middle=true]:rounded-none"
                                    x-text="day.label"
                                ></button>
                            </td>
                        </template>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    @if ($name)
        <template x-if="mode === 'single'">
            <input type="hidden" name="{{ $name }}" x-bind:value="value ?? ''" />
        </template>
        <template x-if="mode === 'multiple'">
            <span>
                <template x-for="selected in value ?? []" :key="selected">
                    <input type="hidden" name="{{ $name }}[]" x-bind:value="selected" />
                </template>
            </span>
        </template>
        <template x-if="mode === 'range'">
            <span>
                <input type="hidden" name="{{ $name }}[from]" x-bind:value="value?.from ?? ''" />
                <input type="hidden" name="{{ $name }}[to]" x-bind:value="value?.to ?? ''" />
            </span>
        </template>
    @endif
</div>
