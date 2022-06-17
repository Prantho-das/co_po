<template>
    <Head title="Mark Assign" />

    <BreezeAuthenticatedLayout>
        <template #header> Assign Marks </template>

        <div class="flex flex-wrap items-center" v-for="(info, i) in infos" :key="i">
            <div class="px-6 w-60">
                <h2 class="text-lg">{{ info.student_name }}</h2>
                 <h2>Roll:{{ info.student_roll }}</h2>
            </div>
            <div
                class="my-4 flex-1 flex flex-wrap"
                v-for="(mk, id) in info.marks"
                :key="id"
            >
                <div>
                    <BreezeLabel for="mark" :value="mk.exam_name+' ('+mk.total+')'" />
                    <BreezeInput
                        id="mark"
                        type="number"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        :max="mk.total"
                        v-model="info.marks[id].mark"
                        required
                        autocomplete="off"
                    />
                </div>
            </div>
        </div>
        <BreezeButton @click="markCreate"> Submit </BreezeButton>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import BreezeLabel from "@/Components/Label.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        Link,
    },
    data() {
        return {
            infos: [],
        };
    },
    mounted() {
        this.infoSanitize();
    },
    methods: {
        infoSanitize() {
            let info = {};
            this.students.forEach((student) => {
                info["student_name"] = student.name;
                info["student_id"] = student.id;
                info["student_roll"] = student.roll;
                info["marks"] = [];
                this.examAssigns.forEach((exam, i) => {
                    info["marks"].push({
                        exam_id: exam.id,
                        exam_name: exam.name,
                        mark: "",
                        copo_id: exam.copo_id,
                        co_id: exam.co_id,
                        po_id: exam.po_id,
                        t_assign_courses_id: exam.t_assign_courses_id,
                        teacher_id: exam.teacher_id,
                        total: exam.marks,
                    });
                });
                this.infos.push(info);
                info = {};
            });
        },
        markCreate() {
            this.$inertia.post(
                this.route("exam.markStore", this.teacherAssigns.id),
                this.infos,
                { onSuccess: () => this.infoSanitize() }
            );
        },
    },
    props: ["examAssigns", "students", "teacherAssigns"],
};
</script>
