import type { Temporal } from '@js-temporal/polyfill';

// TODO Make Issue immutable, immer?
export interface Issue {
    title: string;
    id: string;
    createdAt: Temporal.Instant;
}
