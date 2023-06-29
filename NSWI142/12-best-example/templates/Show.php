<h1>Notes</h1>

<?php if ($this->notes) { ?>
<table>
	<?php foreach ($this->notes as $id => $note) { ?>
	<tr>
		<td><?= $note ?></td>
		<td>
			<a href="<?= $this->url('Edit', null, [ 'id' => $id ]) ?>">Edit</a>
		</td>
		<td>
			<form action="<?= $this->url('Show', 'delete', [ 'id' => $id ]) ?>" method="post">
				<button type="submit">Delete</button>
			</form>
		</td>
	</tr>
	<?php } ?>
</table>
<?php } else { ?>
<p>Sorry, there are no notes yet.</p>
<?php } ?>

<a href="<?= $this->url('Edit') ?>">Add Note</a>
