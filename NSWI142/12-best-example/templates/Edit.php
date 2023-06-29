<h1><?= ($this->id !== '') ? 'Edit' : 'Add' ?> Note</h1>

<form action="<?= $this->url(null, 'edit', [ 'id' => $this->id ]) ?>" method="post">
<table>
	<tr>
		<td class="fullwidth"><input type="text" name="text" value="<?= $this->text ?>"></td>
		<td><button type="submit">Save</button></td>
	</tr>
</table>
</form>
