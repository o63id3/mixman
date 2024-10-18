<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CreateFormLayout from '@/Layouts/CreateFormLayout.vue'

import { formSchema } from './definitions'
import NetworkForm from './Partials/NetworkForm.vue'

import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الشبكات',
    route: route('networks.index'),
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
      :route="route('networks.store')"
    >
      <NetworkForm />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
