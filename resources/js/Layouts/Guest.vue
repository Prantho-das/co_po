<template>
    <div class="bg-gray-50 pt-8">
        <Link href="/"
            ><h2 class="text-2xl text-bold text-center">Co-Po</h2></Link
        >
        <div class="flex items-center min-h-screen">
            <div
                class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl"
            >
                <slot />
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link,
    },
};
</script>
