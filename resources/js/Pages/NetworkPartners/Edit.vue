<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Data, Network } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import NetworkForm from './Partials/NetworkForm.vue'
import { columns } from './partners'
import DataTable from '@/Components/data-table/DataTable.vue'
import Button from '@/Components/ui/button/Button.vue'

const props = defineProps<{
  network: Data<Network>
  can: {
    createPartner: boolean
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    internet_price_per_week: z.any().optional(),
  }),
)

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.network.data.name,
    internet_price_per_week: props.network.data.internet_price_per_week,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('networks.update', props.network.data.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل الشبكة' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Networks" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          {{ network.data.name }}
          <span
            v-if="network.data.manager"
            class="text-xs font-normal tracking-wide"
          >
            ({{ network.data.manager.name }})
          </span>
        </h2>
        <div v-if="can.createPartner">
          <Link :href="route('network.partners.create', network.data.id)">
            <Button> إضافة شريك </Button>
          </Link>
        </div>
      </div>
    </template>

    <UpdateFormLayout @submit="onSubmit" :can-update="true">
      <NetworkForm :disabled="!true" />
    </UpdateFormLayout>

    <div class="mt-4">
      <div class="flex items-center justify-between px-4">
        <p class="text-sm font-medium tracking-wide"># الشركاء</p>
        <!-- <Button
            class="text-xs tracking-wide"
            size="xs"
            :variant="addingForm ? 'outline' : 'default'"
            @click="addingForm = !addingForm"
            v-if="canAddItem"
          >
            <X v-if="addingForm" class="w-3 text-red-500" />
            <span v-else>إضافة رزم</span>
          </Button> -->
      </div>
      <!-- <AddItemsForm
          v-if="addingForm && canAddItem && cards"
          class="mt-4 overflow-hidden bg-white p-6 text-gray-900 shadow-sm sm:rounded-lg"
          :cards="cards"
          :order="order"
          @success="addingForm = false"
        /> -->
      <!-- v-else -->
      <DataTable
        v-if="network.data.partners"
        :data="network.data.partners"
        :columns="columns"
      />
    </div>
  </AuthenticatedLayout>
</template>
