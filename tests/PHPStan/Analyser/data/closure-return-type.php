<?php

namespace ClosureReturnType;

use function PHPStan\Analyser\assertType;

class Foo
{

	public function doFoo(int $i): void
	{
		$f = function () {

		};
		assertType('void', $f());

		$f = function () {
			return;
		};
		assertType('void', $f());

		$f = function () {
			return 1;
		};
		assertType('1', $f());

		$f = function (): array {
			return ['foo' => 'bar'];
		};
		assertType('array(\'foo\' => \'bar\')', $f());

		$f = function (string $s) {
			return $s;
		};
		assertType('string', $f('foo'));

		$f = function () use ($i) {
			return $i;
		};
		assertType('int', $f());

		$f = function () use ($i) {
			if (rand(0, 1)) {
				return $i;
			}

			return null;
		};
		assertType('int|null', $f());

		$f = function () use ($i) {
			if (rand(0, 1)) {
				return $i;
			}

			return;
		};
		assertType('int|null', $f());

		$f = function () {
			yield 1;
			return 2;
		};
		assertType('Generator<int, 1, mixed, 2>', $f());

		$g = function () use ($f) {
			yield from $f();
		};
		assertType('Generator<int, 1, mixed, void>', $g());

		$h = function (): \Generator {
			yield 1;
			return 2;
		};
		assertType('Generator<int, 1, mixed, 2>', $h());
	}

}