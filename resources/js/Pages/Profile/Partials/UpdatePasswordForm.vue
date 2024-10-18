<script setup lang="ts">
import UpdateFormLayout from '@/Layouts/UpdateFormLayout.vue'

import { ref, useTemplateRef } from 'vue'

import { Input } from '@/Components/ui/input'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { CardDescription, CardTitle } from '@/Components/ui/card'

import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

const formSchema = toTypedSchema(
  z
    .object({
      current_password: z.string({ message: 'هذا الحقل مطلوب' }),
      password: z.string({ message: 'هذا الحقل مطلوب' }),
      password_confirmation: z.string({ message: 'هذا الحقل مطلوب' }),
    })
    .refine((data) => data.password === data.password_confirmation, {
      message: 'كلمات المرور لا تتطابق.',
      path: ['password_confirmation'],
    }),
)

const form = useTemplateRef('form')
const currentPasswordInput = ref<InstanceType<typeof Input> | null>(null)
const passwordInput = ref<InstanceType<typeof Input> | null>(null)

const options = {
  onError: (errors: any) => {
    if (errors.password) {
      form.value?.form.resetField('password')
      form.value?.form.resetField('password_confirmation')
      passwordInput.value?.inputElement?.focus()
    }
    if (errors.current_password) {
      currentPasswordInput.value?.inputElement?.focus()
      form.value?.form.resetField('current_password')
    } // Fix: focus resets the errors
  },
}
</script>

<template>
  <section>
    <UpdateFormLayout
      ref="form"
      :form-schema="formSchema"
      :route="route('password.update')"
      :options="options"
      method="put"
    >
      <template #title>
        <CardTitle>تغيير كلمة المرور</CardTitle>
        <CardDescription>تأكد أن كلمة المرور الخاصة بك قوية</CardDescription>
      </template>

      <FormField v-slot="{ componentField }" name="current_password">
        <FormItem class="col-span-full max-w-xl">
          <FormLabel>كلمة المرور الحالية</FormLabel>
          <FormControl>
            <Input
              ref="currentPasswordInput"
              type="password"
              v-bind="componentField"
            />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>
      <FormField v-slot="{ componentField }" name="password">
        <FormItem class="col-span-full max-w-xl">
          <FormLabel>كلمة المرور الجديدة</FormLabel>
          <FormControl>
            <Input
              ref="passwordInput"
              type="password"
              v-bind="componentField"
            />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>
      <FormField v-slot="{ componentField }" name="password_confirmation">
        <FormItem class="col-span-full max-w-xl">
          <FormLabel>تأكيد كلمة المرور الجديدة</FormLabel>
          <FormControl>
            <Input type="password" v-bind="componentField" />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>
    </UpdateFormLayout>
  </section>
</template>
