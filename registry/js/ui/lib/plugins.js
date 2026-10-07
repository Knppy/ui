const registered = new WeakMap();

/**
 * Register an Alpine plugin once, however many components ask for it.
 */
export function use(Alpine, plugin) {
    const plugins = registered.get(Alpine) ?? new Set();

    if (!plugins.has(plugin)) {
        plugins.add(plugin);
        Alpine.plugin(plugin);
    }

    registered.set(Alpine, plugins);
}
