<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CreateFormLayout from '@/Layouts/CreateFormLayout.vue'

import { formSchema } from './definitions'
import UserForm from './Partials/UserForm.vue'

import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { Network } from '@/types'

defineProps<{
  networks: Array<Network>
}>()

const initialValues = {
  percentage: 10,
}

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'المستخدمين',
    route: route('users.index'),
  },
  {
    label: 'إنشاء',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('users.store')"
      class="mt-4"
    >
      <template #default="{ form }">
        <UserForm :role="form.values.role" :networks="networks" />
      </template>
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
