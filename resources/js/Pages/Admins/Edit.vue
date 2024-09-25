<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { User } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import AdminForm from './Partials/AdminForm.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'

const props = defineProps<{
  admin: User
  can: {
    update: boolean
    delete: boolean
  }
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
      .min(4, { message: 'كلمة المرور يجب ان تكون 4 احرف على الاقل' })
      .optional(),
    contact_info: z.string().optional(),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.admin.name,
    username: props.admin.username,
    contact_info: props.admin.contact_info ?? undefined,
    notes: props.admin.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('admins.update', props.admin.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل المستخدم' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Admins" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ admin.name }}
      </h2>
    </template>

    <UpdateFormLayout
      @submit="onSubmit"
      :can-update="can.update"
      :can-delete="can.delete"
    >
      <template #deleteBtn>
        <DeleteLink
          :href="route('admins.destroy', admin.id)"
          @success="toast({ title: 'تم حذف المستخدم' })"
        >
          حذف
        </DeleteLink>
      </template>

      <AdminForm :disabled="!can.update" />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
