<template>
  <Head title="Session" />

  <BreezeAuthenticatedLayout>
    <template #header> All Notifications </template>

    <div class="p-4 bg-white rounded-lg shadow-xs">
      <div class="overflow-hidden mb-8 w-full rounded-lg border shadow-xs">
        <ul class="list-decimal">
          <li
            v-for="(notify, i) in notifications"
            :key="i"
            class="overflow-x-auto w-full p-4 bg-slate-400 mb-4"
          >
            <p>{{ notify.data.message }}</p>
          </li>
        </ul>
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
import "vue-datepicker-ui/lib/vuedatepickerui.css";
import VueDatepickerUi from "vue-datepicker-ui";
export default {
  components: {
    BreezeAuthenticatedLayout,
    Pagination,
    BreezeButton,
    BreezeInput,
    BreezeLabel,
    Datepicker: VueDatepickerUi,
  },
  data() {},
  methods: {},
  props: {
    notifications: Array,
  },
  mounted() {},
};
</script>
