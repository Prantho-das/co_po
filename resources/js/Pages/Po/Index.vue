<template>
    <Head title="Program Outcome" />

    <BreezeAuthenticatedLayout>
        <template #header> Program Outcome </template>

        <div class="p-4 bg-white rounded-lg shadow-xs">
            <div
                class="inline-flex overflow-hidden mb-4 w-full bg-white rounded-lg shadow-md"
            >
                <div class="flex justify-center items-center w-12 bg-blue-500">
                    <svg
                        class="w-6 h-6 text-white fill-current"
                        viewBox="0 0 40 40"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"
                        ></path>
                    </svg>
                </div>

                <div class="px-4 py-2 -mx-3">
                    <div class="mx-3">
                        <span class="font-semibold text-blue-500">Info</span>
                        <p class="text-sm text-gray-600">Sample table page</p>
                    </div>
                </div>
            </div>
            <form @submit.prevent="coCreate" class="mb-4">
                <h3 class="text-lg">Create Program Outcome</h3>
                <div class="my-4">
                    <BreezeLabel for="year" value="Po Name" />
                    <BreezeInput
                        id="year"
                        type="text"
                        class="block w-full mt-1 p-1.5 border-2 border-indigo-100"
                        v-model="form.name"
                        required
                        autopomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="position" value="Po No/Position" />
                    <BreezeInput
                        id="position"
                        type="text"
                        class="block w-full mt-1 p-1.5 border-2 border-indigo-100"
                        v-model="form.po_no"
                        required
                        autopomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.po_no">
                        {{ form.errors.po_no }}
                    </h2>
                </div>
                <BreezeButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Create
                </BreezeButton>
            </form>
            <div
                class="overflow-hidden mb-8 w-full rounded-lg border shadow-xs"
            >
                <div class="overflow-x-auto w-full">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b"
                            >
                                <th class="px-4 py-3">*</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Po No</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            <tr
                                v-for="(po, i) in pos.data"
                                :key="po.id"
                                class="text-gray-700"
                            >
                                <td class="px-4 py-3 text-sm">
                                    {{ i + 1 }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ po.po_name }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ po.po_no }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div
                    class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-pols-9"
                >
                    <pagination :links="pos.links" />
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import BreezeLabel from "@/Components/Label.vue";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
    },
    data() {
        return {
            form: this.$inertia.form({ name: "", po_no: "" }),
        };
    },
    methods: {
        coCreate() {
            this.form.post(this.route("po.store"), {
                onSuccess: () => this.form.reset(),
            });
        },
    },
    props: {
        pos: Object,
    },
};
</script>
