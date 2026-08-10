import { reactive } from 'vue';

export const toasts = reactive([]);

let seed = 0;

export function toast(message, tone = 'success', title = '') {
    const id = ++seed;
    toasts.push({ id, message, tone, title });
    setTimeout(() => dismiss(id), 4000);
    return id;
}

export function dismiss(id) {
    const index = toasts.findIndex((t) => t.id === id);
    if (index !== -1) toasts.splice(index, 1);
}
