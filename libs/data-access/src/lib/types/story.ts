import type { Temporal } from '@js-temporal/polyfill';

// TODO Make Issue immutable, immer?
export interface Story {
    title: string;
    id: string;
    createdAt: Temporal.Instant;
}
