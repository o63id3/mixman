<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import UserForm from './Partials/UserForm.vue'

import { toast } from '@/Components/ui/toast'
import { DeleteLink, SecondaryLink } from '@/Components/links'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

import { Network, User } from '@/types'

const props = defineProps<{
  user: User
  networks: Array<Network>
  can: {
    update: boolean
    delete: boolean
  }
}>()

const initialValues = {
  name: props.user.name,
  username: props.user.username,
  telegram: props.user.telegram,
  role: props.user.role,
  network_id: props.user.network ? String(props.user.network?.id) : undefined,
  percentage: props.user.percentage,
  contact_info: props.user.contact_info,
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ user.name }}
      </h2>
    </template>

    <UpdateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('users.update', user.id)"
      @success="toast({ title: 'تم تعديل المستخدم' })"
      :can-update="can.update"
    >
      <template #buttons>
        <DeleteLink
          v-if="user.active"
          :href="route('users.deactivate', user.id)"
          @success="toast({ title: 'تم تعطيل الحساب' })"
          method="POST"
        >
          تعطيل
        </DeleteLink>
        <SecondaryLink
          v-else
          method="DELETE"
          :href="route('users.activate', user.id)"
          @success="toast({ title: 'تم تفعيل الحساب' })"
        >
          تفعيل
        </SecondaryLink>
      </template>

      <template #default="{ values }">
        <UserForm
          :disabled="!can.update"
          :role="values.role"
          :networks="networks"
        />
      </template>
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
