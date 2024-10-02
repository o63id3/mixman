<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Data, Network, User } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import AdminForm from './Partials/AdminForm.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import SecondaryLink from '@/Components/links/SecondaryLink.vue'

const props = defineProps<{
  admin: User
  networks: Array<Network>
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
    role: z.string({ message: 'هذا الحقل مطلوب' }),
    network_id: z.string({ message: 'هذا الحقل مطلوب' }).optional(),
    percentage: z.number({ message: 'هذا الحقل مطلوب' }).optional(),
    contact_info: z.string().optional(),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors, values } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.admin.name,
    username: props.admin.username,
    role: props.admin.role,
    network_id: String(props.admin.network?.id),
    percentage: props.admin.percentage,
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

    <UpdateFormLayout @submit="onSubmit" :can-update="can.update">
      <template #buttons>
        <DeleteLink
          v-if="admin.active"
          :href="route('users.deactivate', admin.id)"
          @success="toast({ title: 'تم تعطيل الحساب' })"
          method="POST"
        >
          تعطيل الحساب
        </DeleteLink>
        <SecondaryLink
          v-else
          method="DELETE"
          :href="route('users.activate', admin.id)"
          @success="toast({ title: 'تم تفعيل الحساب' })"
        >
          تفعيل الحساب
        </SecondaryLink>
      </template>

      <AdminForm
        :disabled="!can.update"
        :role="values.role"
        :networks="networks"
      />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
