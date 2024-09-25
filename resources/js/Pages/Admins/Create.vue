<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import AdminForm from './Partials/AdminForm.vue'

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    username: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'اسم المستخدم يجيب ان يكون حرفين على الاقل' }),
    password: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(4, { message: 'كلمة المرور يجب ان تكون 4 احرف على الاقل' }),
    contact_info: z.string().optional(),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors } = useForm({
  validationSchema: formSchema,
})

const onSubmit = handleSubmit((values) => {
  router.post(route('admins.store'), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم إنشاء المستخدم' })
      resetForm()
    },
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Admins" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        مستخدم جديد
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit">
      <AdminForm />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
