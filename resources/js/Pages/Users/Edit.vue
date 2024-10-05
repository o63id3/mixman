<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Network, User } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import UserForm from './Partials/UserForm.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import SecondaryLink from '@/Components/links/SecondaryLink.vue'
import { useSubmit } from '@/Components/Composables/submit'

const props = defineProps<{
  user: User
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
    name: props.user.name,
    username: props.user.username,
    role: props.user.role,
    network_id: props.user.network ? String(props.user.network?.id) : undefined,
    percentage: props.user.percentage,
    contact_info: props.user.contact_info ?? undefined,
    notes: props.user.notes ?? undefined,
  },
})

const { submit, loading } = useSubmit(
  route('users.update', props.user.id),
  undefined,
  setErrors,
  'تم تعديل المستخدم',
  'patch',
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <Head title="Users" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ user.name }}
      </h2>
    </template>

    <UpdateFormLayout
      @submit="onSubmit"
      :loading="loading"
      :can-update="can.update"
    >
      <template #buttons>
        <DeleteLink
          v-if="user.active"
          :href="route('users.deactivate', user.id)"
          @success="toast({ title: 'تم تعطيل الحساب' })"
          method="POST"
        >
          تعطيل الحساب
        </DeleteLink>
        <SecondaryLink
          v-else
          method="DELETE"
          :href="route('users.activate', user.id)"
          @success="toast({ title: 'تم تفعيل الحساب' })"
        >
          تفعيل الحساب
        </SecondaryLink>
      </template>

      <UserForm
        :disabled="!can.update"
        :role="values.role"
        :networks="networks"
      />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
