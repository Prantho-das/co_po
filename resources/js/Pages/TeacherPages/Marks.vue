<template>
    <Head title="Mark Assign" />

    <BreezeAuthenticatedLayout>
        <template #header> Assign Marks </template>

        <div class="flex justify-between" v-for="(info, i) in infos" :key="i">
            <h2>{{ info.student_name }}</h2>
            <div v-for="(mk, id) in info.marks" :key="id">
                <h2>{{ mk.exam_name }}</h2>
                <h2>Total{{ mk.id }}</h2>
                <input
                    type="number"
                    min="0"
                    :max="mk.total"
                    v-model="info.marks[id].mark"
                />
            </div>
        </div>
        <button @click="markCreate">done</button>
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
            //form: this.$inertia.form({ name: "", marks: "", copo: "" }),
        };
    },
    mounted() {
        this.markp();
    },
    methods: {
        markp() {
            let info = {};
            this.students.forEach((student) => {
                info["student_name"] = student.name;
                info["student_id"] = student.id;
                info["marks"] = [];
                this.examAssigns.forEach((exam, i) => {
                    info["marks"].push({
                        exam_id: exam.id,
                        exam_name: exam.name,
                        mark: "",
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
           this.$inertia.post(this.route("exam.markStore", this.teacherAssigns.id),this.infos,
                { onSuccess: () => alert("o") }
            );
        },
    },
    props: ["examAssigns", "students", "teacherAssigns"],
};
</script>
