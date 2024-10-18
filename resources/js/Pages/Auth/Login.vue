<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue'

defineOptions({
  layout: GuestLayout,
})

import { onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import { Checkbox } from '@/Components/ui/checkbox'
import InputError from '@/Components/InputError.vue'
import { Input } from '@/Components/ui/input'
import { Button } from '@/Components/ui/button'
import { Label } from '@/Components/ui/label'

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

const input = ref<InstanceType<typeof Input> | null>(null)
onMounted(() => {
  input.value?.inputElement?.focus()
})
</script>

<template>
  <form @submit.prevent="submit">
    <div>
      <Label for="username">اسم المستخدم</Label>

      <Input
        ref="input"
        type="text"
        class="mt-2 block w-full"
        autocapitalize="none"
        v-model="form.username"
      />

      <InputError class="mt-2" :message="form.errors.username" />
    </div>

    <div class="mt-4">
      <Label for="password">كلمة المرور</Label>

      <Input
        type="password"
        class="mt-2 block w-full"
        v-model="form.password"
        autofocus
      />

      <InputError class="mt-2" :message="form.errors.password" />
    </div>

    <div class="mt-4 block">
      <Label class="flex items-center">
        <Checkbox name="remember" v-model="form.remember" />
        <span class="ms-2 text-sm text-gray-600">تذكرني</span>
      </Label>
    </div>

    <div class="mt-4 flex items-center justify-end">
      <Button
        class="ms-4"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
        :loading="form.processing"
      >
        دخول
      </Button>
    </div>
  </form>
</template>
