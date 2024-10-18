<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import UpdateFormLayout from '@/Layouts/UpdateFormLayout.vue'

import { formSchema } from './definitions'
import ExpenseForm from './Partials/ExpenseForm.vue'

import { toast } from '@/Components/ui/toast'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import { DeleteLink } from '@/Components/links'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { AlertCircle } from 'lucide-vue-next'

import { Expense, Network } from '@/types'

const props = defineProps<{
  expense: Expense
  networks: Array<Network>
  can: {
    delete: boolean
  }
}>()

const initialValues = {
  description: props.expense.description,
  network_id: String(props.expense.network.id),
  amount: props.expense.amount,
}

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'المصروفات',
    route: route('expenses.index'),
  },
  {
    label: `${props.expense.id}#`,
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
    </template>

    <Alert
      v-if="expense.can.update"
      class="rounded-none sm:rounded-xl"
      variant="destructive"
    >
      <AlertCircle class="h-4 w-4" />
      <AlertTitle>انتباه!</AlertTitle>
      <AlertDescription>
        يمكنك تعديل أو حذف هذا المصروف حتى الساعة
        <span class="font-bold">11:59</span>!
      </AlertDescription>
    </Alert>

    <UpdateFormLayout
      class="mt-4"
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('expenses.update', expense.id)"
    >
      <template #buttons>
        <DeleteLink
          v-if="can.delete"
          :href="route('expenses.destroy', expense.id)"
          @success="toast({ title: 'تم حذف المصروف' })"
        >
          حذف
        </DeleteLink>
      </template>

      <ExpenseForm :networks="networks" />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
