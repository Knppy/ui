let componentId = 0;

/**
 * Get a unique id for linking the parts of a component together.
 */
export const nextId = () => ++componentId;
