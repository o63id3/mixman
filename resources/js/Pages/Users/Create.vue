<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import UserForm from './Partials/UserForm.vue'
import { Network } from '@/types'
import { useSubmit } from '@/Composables/submit'
import { toast } from '@/Components/ui/toast'

defineProps<{
  networks: Array<Network>
}>()

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
    role: z.string({ message: 'هذا الحقل مطلوب' }),
    network_id: z.string({ message: 'هذا الحقل مطلوب' }).optional(),
    percentage: z.number({ message: 'هذا الحقل مطلوب' }).optional(),
    contact_info: z.string().optional(),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors, values } = useForm({
  validationSchema: formSchema,
  initialValues: {
    percentage: 10,
  },
})

const { submit, loading } = useSubmit(route('users.store'), {
  method: 'post',
  onSuccess: () => toast({ title: 'تم إنشاء المستخدم' }),
  onError: (errors) => setErrors(errors),
})
const onSubmit = handleSubmit(submit)
</script>

<template>
  <Head title="Admins" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        مستخدم جديد
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit" :loading="loading">
      <UserForm :role="values.role" :networks="networks" />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
