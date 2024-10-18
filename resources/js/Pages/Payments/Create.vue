<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CreateFormLayout from '@/Layouts/CreateFormLayout.vue'

import { formSchema } from './definitions'
import PaymentForm from './Partials/PaymentForm.vue'

import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { User } from '@/types'

defineProps<{
  users: Array<User>
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الدفعات',
    route: route('payments.index'),
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
      :route="route('payments.store')"
      btn-title="إضافة"
    >
      <template #default="{ form }">
        <PaymentForm
          :users="users"
          :selected="form.values.user_id"
          @select="
            (selected: number) => form.setFieldValue('user_id', selected)
          "
        />
      </template>
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
