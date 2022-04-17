type ArrayMapCallback<I, O> = (value: I, index: number, array: I[]) => O;

export function mapArray<I, O>(callback: ArrayMapCallback<I, O>): (array: I[]) => O[] {
    return (array: Array<I>) => array.map(callback);
}
