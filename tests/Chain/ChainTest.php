<?php

use Formigone\Chain;

class ChainTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function get_internalArrayIsEmpty_ReturnsEmptyArray()
    {
        $chain = Chain::from([]);

        $data = $chain->get();

        self::assertEmpty($data);
    }

    /**
     * @test
     */
    public function get_internalArrayIsNotEmpty_ReturnsArrayAsIs()
    {
        $in = ['lang' => 'PHP', 'lib' => 'Chain'];
        $chain = Chain::from($in);

        $data = $chain->get();

        foreach ($in as $key => $val) {
            self::assertArrayHasKey($key, $data);
            self::assertEquals($val, $data[$key]);
        }
    }

    /**
     * @test
     */
    public function get_valuesOnlyFlag_ReturnsKeysInNumericKeyArray()
    {
        $in = ['lang' => 'PHP', 'lib' => 'Chain'];
        $chain = Chain::from($in);

        $data = $chain->get(true);

        $index = 0;
        self::assertEquals(count($in), count($data));
        foreach ($data as $key => $val) {
            self::assertEquals($index, $key);
            self::assertTrue(in_array($val, $in));
            $index += 1;
        }
    }

    /**
     * @test
     */
    public function map_predicateReceivesValueIndexAndCollection()
    {
        $in = ['lang' => 'PHP', 'lib' => 'Chain'];
        $chain = Chain::from($in);
        $predicate = function($value, $key, array $collection) use ($in) {
            self::assertArrayHasKey($key, $collection);
            self::assertEquals($value, $collection[$key]);
            self::assertEquals($value, $in[$key]);
        };

        $chain->map($predicate);
    }

    /**
     * @test
     */
    public function map_iteratesOverInternalArrayNthTimes()
    {
        $in = ['lang' => 'PHP', 'lib' => 'Chain'];
        $chain = Chain::from($in);

        $out = $chain->map(function($value) {
            return strtolower($value);
        })->get();

        self::assertEquals(count($out), count($in));

        foreach ($in as $val) {
            self::assertTrue(in_array(strtolower($val), $out));
        }
    }

    /**
     * @test
     */
    public function map_attemptsToMutateInternalArrayMidIteration_internalArrayIsUntouched()
    {
        $in = ['lang' => 'PHP', 'lib' => 'Chain'];
        $chain = Chain::from($in);

        $out = $chain->map(function($value, $key, &$array) {
            $array[$key . '-v2'] = $value . '-v2';
            return $value;
        })->get();

        self::assertEquals(count($in), count($out));
    }

    /**
     * @test
     */
    public function map_producesSameOutputAsNativeMap()
    {
        $in = ['lang' => 'PHP', 'lib' => 'Chain'];
        $chain = Chain::from($in);

        $toUpper = function($value) {
            return strtoupper($value);
        };

        $mine = $chain->map($toUpper)->get();
        $theirs = array_map($toUpper, $in);

        self::assertEquals(json_encode($theirs), json_encode($mine));
    }

    /**
     * @test
     */
    public function filter_iteratesOverInternalArrayNthTimes()
    {
        $in = ['lang' => 'PHP', 'lib' => 'Chain'];
        $chain = Chain::from($in);

        $out = $chain->filter(function($value) {
            return true;
        })->get();

        self::assertEquals(count($out), count($in));

        foreach ($in as $key => $val) {
            self::assertTrue(in_array($val, $out));
            self::assertArrayHasKey($key, $out);
        }
    }

    /**
     * @test
     */
    public function filter_FilterValuesInPredicate_OnlyReturnsTruthyOutputFromPredicate()
    {
        $in = ['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5];
        $chain = Chain::from($in);

        $oddValues = $chain->filter(function($value) {
            return $value % 2;
        });

        $withKeys = $oddValues->get();
        $valuesOnly = $oddValues->get(true);

        self::assertEquals([1, 3, 5], array_values($withKeys));
        self::assertEquals(['one', 'three', 'five'], array_keys($withKeys));

        self::assertEquals([1, 3, 5], array_values($valuesOnly));
        self::assertEquals([0, 1, 2], array_keys($valuesOnly));
    }

    /**
     * @test
     */
    public function filter_FilterKeysInPredicate_OnlyReturnsTruthyOutputFromPredicate()
    {
        $in = ['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5];
        $chain = Chain::from($in);

        $evenValues = $chain->filter(function($value, $key) {
            return in_array($key, ['two', 'four']);
        });

        $withKeys = $evenValues->get();
        $valuesOnly = $evenValues->get(true);

        self::assertEquals([2, 4], array_values($withKeys));
        self::assertEquals(['two', 'four'], array_keys($withKeys));

        self::assertEquals([2, 4], array_values($valuesOnly));
        self::assertEquals([0, 1], array_keys($valuesOnly));
    }

    /**
     * @test
     */
    public function filter_attemptsToMutateInternalArrayMidIteration_internalArrayIsUntouched()
    {
        $in = ['lang' => 'PHP', 'lib' => 'Chain'];
        $chain = Chain::from($in);

        $out = $chain->filter(function($value, $key, &$array) {
            $array[$key . '-v2'] = $value . '-v2';
            return true;
        })->get();

        self::assertEquals(count($in), count($out));
    }

    /**
     * @test
     */
    public function filter_producesSameOutputAsNativeFilter()
    {
        $in = ['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5];
        $chain = Chain::from($in);

        $twoThreeFour = function($value) {
            return $value > 1 && $value < 5;
        };

        $mine = $chain->filter($twoThreeFour)->get();
        $theirs = array_filter($in, $twoThreeFour);

        self::assertEquals(json_encode($theirs), json_encode($mine));
    }
}
