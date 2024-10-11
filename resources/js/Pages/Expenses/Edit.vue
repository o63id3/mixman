<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { useForm } from 'vee-validate'
import { formSchema } from './definitions'
import { useSubmit } from '@/Composables/submit'
import ExpenseForm from './Partials/ExpenseForm.vue'

import { toast } from '@/Components/ui/toast'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
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

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    description: props.expense.description,
    network_id: String(props.expense.network.id),
    amount: props.expense.amount,
  },
})

const { submit, loading } = useSubmit(
  route('expenses.update', props.expense.id),
  {
    method: 'patch',
    onSuccess: () => toast({ title: 'تم تعديل المصروف' }),
    onError: (errors) => setErrors(errors),
  },
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية رقم {{ expense.id }}
      </h2>
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
      @submit="onSubmit"
      :loading="loading"
      can-update
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
