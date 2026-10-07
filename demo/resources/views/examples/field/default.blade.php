<div class="w-full max-w-md">
    <form>
        <x-ui.field-group>
            <x-ui.field-set>
                <x-ui.field-legend>Payment Method</x-ui.field-legend>
                <x-ui.field-description> All transactions are secure and encrypted </x-ui.field-description>
                <x-ui.field-group>
                    <x-ui.field>
                        <x-ui.field-label for="checkout-7j9-card-name-43j"> Name on Card </x-ui.field-label>
                        <x-ui.input id="checkout-7j9-card-name-43j" placeholder="Evil Rabbit" required />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.field-label for="checkout-7j9-card-number-uw1"> Card Number </x-ui.field-label>
                        <x-ui.input id="checkout-7j9-card-number-uw1" placeholder="1234 5678 9012 3456" required />
                        <x-ui.field-description> Enter your 16-digit card number </x-ui.field-description>
                    </x-ui.field>
                    {{--                    <div class="grid grid-cols-3 gap-4">--}}
                    {{--                        <x-ui.field>--}}
                    {{--                            <x-ui.field-label for="checkout-exp-month-ts6">--}}
                    {{--                                Month--}}
                    {{--                            </x-ui.field-label>--}}
                    {{--                            <x-ui.select items={months}>--}}
                    {{--                                <x-ui.select-trigger id="checkout-exp-month-ts6">--}}
                    {{--                                    <x-ui.select-value />--}}
                    {{--                                </x-ui.select-trigger>--}}
                    {{--                                <x-ui.select-content>--}}
                    {{--                                    <x-ui.select-group>--}}
                    {{--                                        {months.map((item) => (--}}
                    {{--                                        <x-ui.select-item key={item.value} value={item.value}>--}}
                    {{--                                            {item.label}--}}
                    {{--                                        </x-ui.select-item>--}}
                    {{--                                        ))}--}}
                    {{--                                    </x-ui.select-group>--}}
                    {{--                                </x-ui.select-content>--}}
                    {{--                            </x-ui.select>--}}
                    {{--                        </x-ui.field>--}}
                    {{--                        <x-ui.field>--}}
                    {{--                            <x-ui.field-label for="checkout-7j9-exp-year-f59">--}}
                    {{--                                Year--}}
                    {{--                            </x-ui.field-label>--}}
                    {{--                            <x-ui.select items={years}>--}}
                    {{--                                <x-ui.select-trigger id="checkout-7j9-exp-year-f59">--}}
                    {{--                                    <x-ui.select-value />--}}
                    {{--                                </x-ui.select-trigger>--}}
                    {{--                                <x-ui.select-content>--}}
                    {{--                                    <x-ui.select-group>--}}
                    {{--                                        {years.map((item) => (--}}
                    {{--                                        <x-ui.select-item key={item.value} value={item.value}>--}}
                    {{--                                            {item.label}--}}
                    {{--                                        </x-ui.select-item>--}}
                    {{--                                        ))}--}}
                    {{--                                    </x-ui.select-group>--}}
                    {{--                                </x-ui.select-content>--}}
                    {{--                            </x-ui.select>--}}
                    {{--                        </x-ui.field>--}}
                    {{--                        <x-ui.field>--}}
                    {{--                            <x-ui.field-label for="checkout-7j9-cvv">CVV</x-ui.field-label>--}}
                    {{--                            <x-ui.input id="checkout-7j9-cvv" placeholder="123" required />--}}
                    {{--                        </x-ui.field>--}}
                    {{--                    </div>--}}
                </x-ui.field-group>
            </x-ui.field-set>
            <x-ui.field-separator />
            <x-ui.field-set>
                <x-ui.field-legend>Billing Address</x-ui.field-legend>
                <x-ui.field-description>
                    The billing address associated with your payment method
                </x-ui.field-description>
                <x-ui.field-group>
                    <x-ui.field orientation="horizontal">
                        <x-ui.checkbox id="checkout-7j9-same-as-shipping-wgm" checked />
                        <x-ui.field-label for="checkout-7j9-same-as-shipping-wgm" class="font-normal">
                            Same as shipping address
                        </x-ui.field-label>
                    </x-ui.field>
                </x-ui.field-group>
            </x-ui.field-set>
            <x-ui.field-set>
                <x-ui.field-group>
                    <x-ui.field>
                        <x-ui.field-label for="checkout-7j9-optional-comments"> Comments </x-ui.field-label>
                        <x-ui.textarea
                            id="checkout-7j9-optional-comments"
                            placeholder="Add any additional comments"
                            class="resize-none"
                        />
                    </x-ui.field>
                </x-ui.field-group>
            </x-ui.field-set>
            <x-ui.field orientation="horizontal">
                <x-ui.button type="submit">Submit</x-ui.button>
                <x-ui.button variant="outline" type="button"> Cancel </x-ui.button>
            </x-ui.field>
        </x-ui.field-group>
    </form>
</div>
