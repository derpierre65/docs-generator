<template>
	<div class="endpoint-example xl:sticky xl:top-20">
		<slot />
	</div>
</template>

<script>
export default {
	name: 'EndpointExample',
	mounted() {
		if (this.$parent.$parent.$options.name === 'Endpoint') {
			this.$parent.$parent.renderLayout();
		}
	},
};
</script>