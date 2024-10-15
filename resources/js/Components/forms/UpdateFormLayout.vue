<script setup lang="ts" generic="TValues">
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card'

import { useSubmit } from '@/Composables/submit'
import { FormContext, TypedSchema, useForm } from 'vee-validate'
import { Separator } from '../ui/separator'

import { type Method, VisitOptions } from '@inertiajs/core'

const props = withDefaults(
  defineProps<{
    formSchema: TypedSchema
    initialValues?: TValues
    route: string
    canUpdate?: boolean
    method?: Method
    options?: VisitOptions
  }>(),
  {
    canUpdate: true,
    method: 'patch',
  },
)

const emit = defineEmits(['success'])

const form = useForm({
  validationSchema: props.formSchema,
  initialValues: props.initialValues,
})

defineExpose<{
  form: FormContext
}>({
  form: form,
})

const { submit, loading, recentlySuccessful } = useSubmit(props.route, {
  method: props.method,
  onSuccess: () => emit('success'),
  onError: (errors) => {
    form.setErrors(errors)

    if (props.options?.onError) props.options.onError(errors)
  },
})
const onSubmit = form.handleSubmit(submit)
</script>

<template>
  <form @submit="onSubmit">
    <Card>
      <CardHeader v-if="$slots.title">
        <slot name="title" />
      </CardHeader>
      <CardContent>
        <div
          class="grid grid-cols-1 gap-4 md:grid-cols-2"
          :class="{
            'pt-6': !$slots.title,
          }"
        >
          <slot :form="form" />
        </div>
      </CardContent>
      <CardFooter class="border-t px-6 py-4">
        <div class="flex items-center gap-4">
          <Button
            v-if="canUpdate"
            type="submit"
            :loading="loading"
            :disabled="
              loading || !form.meta.value.dirty || !form.meta.value.valid
            "
          >
            حفظ
          </Button>

          <Separator
            v-if="$slots.buttons && canUpdate"
            orientation="vertical"
            class="h-4"
          />

          <slot name="buttons" />

          <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
          >
            <p v-if="recentlySuccessful" class="text-sm text-gray-600">
              تم الحفظ.
            </p>
          </Transition>
        </div>
      </CardFooter>
    </Card>
  </form>
</template>
