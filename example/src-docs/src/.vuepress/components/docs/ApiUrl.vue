<template>
	<div>
		<Badge :type="badgeColor" :text="type.toUpperCase()" vertical="top" />
		<code>https://api.yagt.space/v1{{endpoint}}</code>
	</div>
</template>

<script>
export default {
	name: 'ApiUrl',
	props: {
		type: {
			type: String,
			default: '',
		},
		endpoint: {
			type: String,
			default: '',
		},
	},
	computed: {
		badgeColor() {
			if (this.type === 'get') {
				return 'notice';
			}
			else if (this.type === 'post') {
				return 'tip';
			}
			else if (this.type === 'patch') {
				return 'warning';
			}
			else if (this.type === 'delete') {
				return 'danger';
			}
		},
	},
};
</script>