<script setup lang="ts">
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/ui/button/Button.vue'
import Input from '@/Components/ui/input/Input.vue'
import Label from '@/Components/ui/label/Label.vue'
import { useForm, usePage } from '@inertiajs/vue3'

defineProps<{
  status?: String
}>()

const user = usePage().props.auth.user

const form = useForm({
  name: user.name,
  username: user.username,
})
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">معلومات الحساب</h2>

      <p class="mt-1 text-sm text-gray-600">حدث اسمك واسم المستخدم الخاص بك</p>
    </header>

    <form
      @submit.prevent="form.patch(route('profile.update'))"
      class="mt-6 space-y-6"
    >
      <div>
        <Label for="name">اسمك</Label>

        <Input
          id="name"
          type="text"
          class="mt-2 block w-full"
          v-model="form.name"
          autofocus
        />

        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div>
        <Label for="username">اسم المستخدم</Label>

        <Input
          id="username"
          type="text"
          class="mt-2 block w-full"
          autocapitalize="none"
          v-model="form.username"
        />

        <InputError class="mt-2" :message="form.errors.username" />
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
