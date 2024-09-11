<script setup lang="ts">
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/ui/button/Button.vue'
import Input from '@/Components/ui/input/Input.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const passwordInput = ref<HTMLInputElement | null>(null)
const currentPasswordInput = ref<HTMLInputElement | null>(null)

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const updatePassword = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    },
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation')
        passwordInput.value?.focus()
      }
      if (form.errors.current_password) {
        form.reset('current_password')
        currentPasswordInput.value?.focus()
      }
    },
  })
}
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">تغيير كلمة المرور</h2>

      <p class="mt-1 text-sm text-gray-600">
        تأكد أن كلمة المرور الخاصة بك قوية
      </p>
    </header>

    <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
      <div>
        <Label for="current_password">كلمة المرور الحالية</Label>

        <Input
          id="current_password"
          ref="currentPasswordInput"
          v-model="form.current_password"
          type="password"
          class="mt-2 block w-full"
        />

        <InputError :message="form.errors.current_password" class="mt-2" />
      </div>

      <div>
        <Label for="password">كلمة المرور الجديدة</Label>

        <Input
          id="password"
          ref="passwordInput"
          v-model="form.password"
          type="password"
          class="mt-2 block w-full"
        />

        <InputError :message="form.errors.password" class="mt-2" />
      </div>

      <div>
        <Label for="password_confirmation">تأكيد كلمة المرور الجديدة</Label>

        <Input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="mt-2 block w-full"
        />

        <InputError :message="form.errors.password_confirmation" class="mt-2" />
      </div>

      <div class="flex items-center gap-4">
        <Button :disabled="form.processing">حفظ</Button>

        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
            تم الحفظ.
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
