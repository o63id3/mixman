<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import ExpenseForm from './Partials/ExpenseForm.vue'

import { toast } from '@/Components/ui/toast'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import { DeleteLink } from '@/Components/links'

import { Expense, Network } from '@/types'
import { AlertCircle } from 'lucide-vue-next'

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
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <Breadcrumb>
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('dashboard')">
              الرئيسة
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('expenses.index')">
              المصروفات
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>{{ expense.id }}#</BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
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
