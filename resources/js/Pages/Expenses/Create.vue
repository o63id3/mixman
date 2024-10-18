<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CreateFormLayout from '@/Layouts/CreateFormLayout.vue'

import { formSchema } from './definitions'
import ExpenseForm from './Partials/ExpenseForm.vue'

import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { Network } from '@/types'

defineProps<{
  networks: Array<Network>
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'النفقات',
    route: route('expenses.index'),
  },
  {
    label: 'إضافة',
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
      :route="route('expenses.store')"
      btn-title="إضافة"
    >
      <ExpenseForm :networks="networks" />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
