<script setup lang="ts">
import { Checkbox } from '@/Components/ui/checkbox'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head, useForm } from '@inertiajs/vue3'
import Input from '@/Components/ui/input/Input.vue'

defineProps<{
  canResetPassword?: boolean
  status?: string
}>()

const form = useForm({
  username: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => {
      form.reset('password')
    },
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="username" value="اسم المستخدم" />

        <Input
          type="text"
          class="mt-2 block w-full"
          v-model="form.username"
          autofocus
        />

        <InputError class="mt-2" :message="form.errors.username" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="كلمة المرور" />

        <Input
          type="password"
          class="mt-2 block w-full"
          v-model="form.password"
          autofocus
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4 block">
        <label class="flex items-center">
          <Checkbox name="remember" v-model="form.remember" />
          <span class="ms-2 text-sm text-gray-600">تذكرني</span>
        </label>
      </div>

      <div class="mt-4 flex items-center justify-end">
        <PrimaryButton
          class="ms-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          دخول
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
