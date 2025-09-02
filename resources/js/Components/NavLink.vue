<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'block px-4 py-2 rounded hover:bg-blue-100 bg-blue-100 '
        : 'block px-4 py-2 rounded hover:bg-blue-100';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
