<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CreateFormLayout from '@/Layouts/CreateFormLayout.vue'

import { formSchema } from './definitions'
import CardForm from './Partials/CardForm.vue'

import { CardTitle } from '@/Components/ui/card'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الكروت',
    route: route('cards.index'),
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

    <CreateFormLayout :form-schema="formSchema" :route="route('cards.store')">
      <template #title>
        <CardTitle> كرت جديد </CardTitle>
      </template>

      <CardForm />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
