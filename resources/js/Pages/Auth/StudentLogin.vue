<template>
  <Head title="Log in for Student"/>

  <div class="flex flex-col overflow-y-auto md:flex-row">
    <div class="h-32 md:h-auto md:w-1/2">
      <img aria-hidden="true" class="object-cover w-full h-full" src="/images/reading.jpg" alt="Office"/>
    </div>
    <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
      <div class="w-full">
        <h1 class="mb-4 text-xl font-semibold text-gray-700">Student Login</h1>

        <BreezeValidationErrors class="mb-4"/>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
          {{ status }}
        </div>

        <form @submit.prevent="submit">
          <div class="mt-4">
            <BreezeLabel for="reg" value="Enter Your Registration"/>
            <BreezeInput
                id="reg"
                type="text"
                class="block w-full mt-1"
                v-model="form.reg"
                required
                autofocus
                autocomplete="username"
            />
          </div>

          <div class="block mt-4">
            <label class="flex items-center">
              <BreezeCheckbox name="remember" v-model:checked="form.remember"/>
              <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
          </div>

          <div class="flex items-center justify-end mt-4">
 <Link

                :href="route('student.register')"
                class="text-sm text-gray-600 underline  hover:text-gray-900"
            >
              Register Now
            </Link>
            <BreezeButton
                class="ml-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
              Log in
            </BreezeButton>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
  layout: BreezeGuestLayout,

  components: {
    BreezeButton,
    BreezeCheckbox,
    BreezeInput,
    BreezeLabel,
    BreezeValidationErrors,
    Head,
    Link,
  },

  data() {
    return {
      form: this.$inertia.form({
        reg: "",
        remember: false,
      }),
    };
  },
 props: {
    canResetPassword: Boolean,
    status: String,
  },
  methods: {
    submit() {
      this.form.post(this.route('student.login'), {
        onFinish: () => this.form.reset(),
      });
    },
  },
};
</script>
